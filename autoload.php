<?php

const SOURCE = 'src';

spl_autoload_register(function ($name) {
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . SOURCE . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';

    if (file_exists($fileName)) {
        require_once $fileName;
    }
});