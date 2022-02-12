<?php

namespace bookking3000\GuzzCache\FileSystem;

interface InFileCache
{

    public function getAllKeys();
    public function hasKey(string $key);

    public function read(string $key);
    public function write(string $key, string $content);
    public function delete(string $key);

    public function initialize();
    public function parseLine(array $itemChunk);
    public function setCachePath(string $cachePath);

}