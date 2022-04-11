<?php
$data = realpath(__DIR__.'/../data');
$docs = realpath(__DIR__.'../docs');
$files = glob("$data/*.json");
require_once 'GraphBuilder.php';
require_once 'Vocab.php';
$builder = new \GraphBuilder\Graph();
foreach ( $files as $file ) {
    $builder->addFile($file);
}
$builder->addInverseLinks('Platform', 'game', 'Game', 'platform');
$builder->addInverseLinks('Platform', 'game', 'Series', 'platform');
$builder->addInverseLinks('Genre', 'game', 'Series', 'genre');
$builder->addInverseLinks('Series', 'game', 'Game', 'series');
$builder->addInverseLinks('Genre', 'game', 'Game', 'genre');
require_once "load_renderers.php";
\GraphRenderer\execute_renders($builder);