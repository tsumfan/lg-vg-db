<?php
namespace GraphBuilder;

/**
 * GraphLinks will pass through gets, sets and calls to their targets.
 */
class GraphLink {
    protected $_graph;
    protected $_id;

    public function __construct($id, $graph) {
        $this->_graph = $graph;
        $this->_id = $id;
    }

    public function _getItem() {
        return $this->_graph->itemGraph[$this->_id];
    }

    public function __call($name, $args) {
        $graphtarget = $this->_graph->itemGraph[$this->_id];
        if ( is_callable( [$graphtarget,$name] ) ) {
            return call_user_func_array( [$graphtarget, $name], $args );
        }
        throw new \Exception("Could not call {$name} against graph target {$this->_id}");
    }

    public function __get($name) {
        if ( $this->_graph->itemGraph[$this->_id] ) {
            return $this->_graph->itemGraph[$this->_id]->{$name};
        }
        return null;
    }

    public function __set($name, $value) {
        if ( $this->graph->itemGraph[$this->_id] ) {
            return $this->graph->itemGraph[$this->_id]->{$name} = $value;
        }
    }
}

class Graph {
    public $vocabNamespace = 'LgVocab';
    public $encountered = 0;
    public $itemGraph = [];
    public $linkObjectExamined = [];

    public function addEntry($obj) {
        if ( is_object($obj) && !empty($obj->{"@type"}) && class_exists("{$this->vocabNamespace}\\".$obj->{'@type'}) ) {
            $id = !empty($obj->{'@id'}) ? $obj->{'@id'} : '_'.($this->encountered++);
            $typeclass = "{$this->vocabNamespace}\\".$obj->{'@type'};
            $entry = new $typeclass;
            $entry->id = $id;
            $entry->type = $obj->{'@type'};
            foreach ( get_object_vars( $obj ) as $property => $value ) {
                if ( is_object($value) ) {
                    $entry->{$property}[0] = $this->addEntry($value);
                } else if ( is_array($value) ) {
                    foreach ( $value as $subvalue ) {
                        $entry->{$property}[] = $this->addEntry($subvalue);
                    }
                } else {
                    //Force string to type possibly here.
                    $entry->{$property} = $value;
                }
            }
            $this->itemGraph[$id] = $entry;
            return $entry;
        } else if ( is_object($obj) && !empty($obj->{'@id'}) ) {
            return new GraphLink($obj->{'@id'}, $this);
        }
    }

    public function addFile($file) {
        $obj = json_decode(file_get_contents($file), false);
        $this->addEntry($obj);
    }

    public function addInverseLinks($type, $property, $inverseType, $inverseProperty) {
        $namespacedType = $this->vocabNamespace . '\\'. $type;
        $namespacedInverseType = $this->vocabNamespace . '\\' . $inverseType;
        foreach ( $this->itemGraph as $item ) {
            if ( get_class($item) == $namespacedType ) {
                $addInverse = array_filter( $this->itemGraph , function($scanItem) use ( $item, $namespacedInverseType, $inverseProperty ) {
                    if ( get_class( $scanItem ) == $namespacedInverseType ) {
                        if ( is_array( $scanItem->{$inverseProperty} ) ) {
                            foreach ( $scanItem->{$inverseProperty} as $scanInverse ) {
                                if ( get_class( $scanInverse ) == "GraphBuilder\\GraphLink" && $scanInverse->id == $item->id ) {
                                    return true;
                                }
                            }
                        }
                    }
                    return false;
                } );
                foreach ( $addInverse as $inverse ) {
                    $item->{$property}[] = $inverse;
                }
            }
        }
    }

    public function getAllByType($type) {
        $vocabType = $this->vocabNamespace . '\\'. $type;
        return array_filter ( $this->itemGraph, function($item) use ($vocabType) { return get_class($item) == $vocabType; } );
    }
}