<?php
namespace GraphRenderer;

class Helpers {
    public static $convert = 'convert';
    public static $already_resize=[];

    public static function sortByGraphName($a, $b) {
        return strcasecmp($a->name, $b->name);
    }

    public static function resizeImage($image, $target, $width, $height, $quality = 82) {
        if ( !empty(self::$already_resize[$target]) ) {
            return [ [] , 0 ];
        }
        $output = [];
        $ret = 0;
        $command = [ self::$convert, $image, '-resize', "{$width}x{$height}", '-quality', $quality, $target ];
        $final_command = implode(' ', array_map('escapeshellarg', $command));
        exec( $final_command, $output, $ret );
        self::$already_resize[$target] = true;
        return [ $output, $ret ];
    }

    public static function esc($html) {
        return htmlspecialchars($html, ENT_QUOTES, 'utf-8');
    }
}