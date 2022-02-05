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
$builder->addInverseLinks('Series', 'game', 'Game', 'series');
$builder->addInverseLinks('Genre', 'game', 'Game', 'genre');
var_dump($builder->itemGraph);