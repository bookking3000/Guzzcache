<?php

namespace bookking3000\Guzzcache\QA;

use bookking3000\Guzzcache\FileSystem\File;
use bookking3000\Guzzcache\GuzzCache;

class Logger
{

    const DEBUG = 'DEBUG';
    const ERROR = 'ERROR';

    protected const DEFAULT_FILE = __DIR__ .
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'logs' .
    DIRECTORY_SEPARATOR . GuzzCache::NAME . '.log';


    public static function writeLog($message, $level = self::DEBUG, $fileName = self::DEFAULT_FILE)
    {
        $date = new \DateTime();
        $date = $date->format("Y-m-d H:i:s");

        self::log($date, $level, $message, $fileName);
    }


    private static function log(string $date, $level, $message, $fileName): void
    {
        if (!file_exists(realpath($fileName))) {
            File::createFile($fileName);
        }

        $file = File::openFile($fileName);
        fwrite($file, "[$date][$level] " . $message . "\n");
        fclose($file);
    }

}