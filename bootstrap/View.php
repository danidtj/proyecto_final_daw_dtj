<?php


namespace Bootstrap;
class View {
    public static function render($file) {
        require_once $file;
    }
}

