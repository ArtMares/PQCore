<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            12.11.2017
 */

spl_autoload_register(function($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    if(QFile::exists(':/' . $file)) {
        require_once($file);
        return true;
    }
    if(file_exists(__DIR__ . '/../' . $file)) {
        require_once($file);
        return true;
    }
    return false;
});