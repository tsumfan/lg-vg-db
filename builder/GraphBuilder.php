<?php
namespace GraphBuilder;

class GraphLink {
    public $graph;
    public $id;

    public function __construct($id, $graph) {
        $this->graph = $graph;
        $this->id = $id;
    }

    public function getItem() {
        return $this->graph->itemGraph[$this->id];
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