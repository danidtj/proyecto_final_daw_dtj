<?php


namespace Bootstrap;
class View {
    public static function render($file) {
        //var_dump($file);
        require_once $file;
    }
}

