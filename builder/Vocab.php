<?php
namespace LgVocab;

abstract class VocabEntry {
    public $type;
    public $id;
    abstract public static function fromString($str);
}

class DefinitionList extends VocabEntry {
    public $entry;
    public static function fromString($str) {
        return new Self;
    }
}

class Thing extends VocabEntry {
    public $name;
    public $description;
    public $link;
    public static function fromString($str) {
        $thing = new Self;
        $thing->name = $str;
        return $thing;
    }
}

class Link extends VocabEntry {
    public $name;
    public $url;
    public $description;
    public static function fromString($str) {
        $link = new Self;
        $link->url = $str;
        $link->name = $str;
        return $link;
    }
}

class Game extends Thing {
    public $genre;
    public $series;
    public $platform;
}

class Series extends Thing {
    public $genre;
    public $series;
    public $game;
}

class Genre extends Thing {
    public $game;
}

class Platform extends Thing {
    public $game;
}