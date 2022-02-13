<?php
namespace LgVocab;

abstract class VocabEntry {
    public $type;
    public $id;
    abstract public static function fromString($str);
    public function castIfString($item, $expectedClass) {
        if ( is_string($item) ) {
            return call_user_func([ 'LgVocab\\'.$expectedClass, 'fromString' ], $item);
        } else {
            return $item;
        }
    }
    function getPropertyArray( $property, $expectedClass ) {
        if ( empty($this->{$property}) ) {
            return [];
        } else if ( is_string($this->{$property}) || is_object($this->{$property}) ) {
            return $this->castIfString( $this->{$property}, $expectedClass );
        } else if ( is_array($this->{$property}) ) {
            $self = $this;
            return array_map( function($entry) use ($self, $expectedClass) {
                return $this->castIfString( $entry, $expectedClass );
            }, $this->{$property} );
        }
    }
}

class DefinitionList extends VocabEntry {
    public $entry;
    public static function fromString($str) {
        return new Self;
    }
}

class Image extends VocabEntry {
    public $name;
    public $caption;
    public static function fromString($str) {
        $item = new Self();
        $item->name = $str;
        return $item;
    }
}

class Thing extends VocabEntry {
    public $name;
    public $altName;
    public $description;
    public $link;
    public $image;
    public static function fromString($str) {
        $thing = new Self;
        $thing->name = $str;
        return $thing;
    }
    public function getFirstImage() {
        if ( !empty($this->image) ) {
            if ( is_string($this->image) || is_object($this->image) ) {
                return $this->castIfString( $this->image, 'Image');
            } else if ( is_array($this->image) ) {
                return $this->castIfString($this->image[0]);
            }
        }
        return null;
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