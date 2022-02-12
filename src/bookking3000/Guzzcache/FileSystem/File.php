<?php

namespace bookking3000\Guzzcache\FileSystem;

class File
{

    public static function createFile($fileName)
    {
        if (!file_exists(dirname($fileName))) {
            mkdir(dirname($fileName), 0777, true);
        }
        file_put_contents($fileName,null);
    }

    /**
     * @param $fileName
     * @return false|resource
     */
    public static function openFile($fileName)
    {
        return fopen($fileName, 'ab');
    }

}