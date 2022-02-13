<?php
namespace GraphRenderer;

class GameRenderer implements Renderer {
    use FileOutput;
    public $graph;
    public function render() {
        return false;
        //genre
        $index = $this->getOutputLocation('gameid.html');
        ob_start();
        include 'template/index.php';
        file_put_contents($index, ob_get_clean());
    }

    public function __construct($graph) {
        $this->graph = $graph;
    }


}