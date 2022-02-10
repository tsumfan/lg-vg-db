<?php
namespace GraphRenderer;

interface Renderer {
    public function render();
    public function __construct($graph);
}

function is_graphlink($obj) {
    return get_class($obj) == 'GraphBuilder\\GraphLink';
}

trait FileOutput {
    public function rawPathToDocs() {
        return realpath(__DIR__.'/../docs/');
    }

    public function getOutputLocation($filename) {
        return $this->rawPathToDocs().'/'.$filename;
    }

    public function ensureOutputDirectory($dirname) {
        if ( !is_dir( $desired = $this->rawPathToDocs . $dirname ) ) {
            mkdir( $desired, 0755, true );
        }
    }

    public function getDataLocation($filename) {
        return realpath(__DIR__.'/../data').'/'.$filename;
    }
}

$renderers = glob (__DIR__.'/renderers/*.php');
foreach ( $renderers as $renderfile ) {
    require_once( $renderfile );
}

function execute_renders($graph) {
    foreach ( get_declared_classes() as $class ) {
        if ( in_array('GraphRenderer\Renderer', class_implements($class) ) ) {
            $renderer = new $class($graph);
            $renderer->render();
        }
    }
}