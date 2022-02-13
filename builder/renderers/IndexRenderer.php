<?php
namespace GraphRenderer;

class IndexRenderer implements Renderer {
    use FileOutput;
    public $graph;
    public function render() {
        $games = $this->graph->getAllByType('Game');
        $series = $this->graph->getAllByType('Series');
        
        $entries = array_merge($games, $series);
        usort($entries, ['GraphRenderer\Helpers', 'sortByGraphName']);

        $index = $this->getOutputLocation('index.html');
        ob_start();
        include 'template/index.php';
        file_put_contents($index, ob_get_clean());
    }

    public function __construct($graph) {
        $this->graph = $graph;
    }
}

abstract class CategoryIndexRenderer implements Renderer {
    use FileOutput;
    public $graph;
    public $graphlist;

    public function render() {
        foreach ( $this->graphlist as $node ) {
            $filename = $this->getOutputLocation( $node->id . '.html' );
            $entries = array_merge( $node->getPropertyArray('game', 'Game'), $node->getPropertyArray('series', 'Series') );
            ob_start();
            include 'template/index.php';
            file_put_contents($filename, ob_get_clean());
        }
    }

    abstract public function __construct($graph);
}

class PlatformIndexRenderer extends CategoryIndexRenderer {
    public function __construct($graph) {
        $this->graph = $graph;
        $this->graphlist = $this->graph->getAllByType('Platform');
    }
}

class GenreIndexRenderer extends CategoryIndexRenderer {
    public function __construct($graph) {
        $this->graph = $graph;
        $this->graphlist = $this->graph->getAllByType('Genre');
    }
}