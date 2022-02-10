<?php
namespace GraphRenderer;

class IndexRenderer implements Renderer {
    use FileOutput;
    public $graph;
    public function render() {
        $index = $this->getOutputLocation('index.html');
        ob_start();
        include 'template/index.php';
        file_put_contents($index, ob_get_clean());
    }

    public function __construct($graph) {
        $this->graph = $graph;
    }
}