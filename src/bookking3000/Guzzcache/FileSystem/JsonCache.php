<?php

namespace bookking3000\Guzzcache\FileSystem;

use bookking3000\Guzzcache\QA\Logger;
use Exception;
use JsonCollectionParser\Parser;

class JsonCache implements InFileCache
{

    const KEY = 'key';
    const RESPONSE = 'response';
    const EXPIRY_TIME = 'expiry_date_time';

    protected string $cachePath = "";
    protected array $content = [];
    protected int $count = 0;
    protected int $lifetime = 2592000; //30 Days (30*24*60*60)

    public function __construct(string $cachePath = "")
    {
        if (strlen($cachePath) != 0)
            $this->setCachePath($cachePath);
    }

    public function setCachePath(string $cachePath): void
    {
        $this->cachePath = $cachePath;
        $fileName = $this->cachePath;

        if (!file_exists(realpath($fileName))) {
            File::createFile($fileName);
        }

        $this->initialize();
    }

    public function setLifetime(int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }

    public function getExpiryTime($key): int
    {
        if (!$this->hasKey($key))
            throw new \LogicException("Key: $key does not exist");

        $aryKey = array_search($key, array_column($this->content, self::KEY));
        return $this->content[$aryKey][self::EXPIRY_TIME];
    }

    public function isExpired(string $key): bool
    {
        $currentTime = time();
        $expiryTime = $this->getExpiryTime($key);

        if ($expiryTime == 0)
            return false;

        if ($currentTime > $expiryTime)
            return true;

        return false;
    }

    public function initialize()
    {
        $this->content = [];
        try {
            $parser = new Parser();
            $parser->parse($this->cachePath, [$this, 'parseLine']);
        } catch (Exception $e) {
            Logger::writeLog($e, Logger::ERROR);
        }
    }

    public function getAllKeys(): array
    {
        $keys = [];
        foreach ($this->content as $item) {
            $keys [] = $item[self::KEY];
        }
        return $keys;
    }

    public function hasKey(string $key): bool
    {
        $keys = $this->getAllKeys();
        if (in_array($key, $keys))
            return true;

        return false;
    }

    public function readResponse(string $key)
    {
        if (!$this->hasKey($key))
            throw new \LogicException("Key: $key does not exist");

        $aryKey = array_search($key, array_column($this->content, self::KEY));
        return $this->content[$aryKey][self::RESPONSE];
    }

    public function write(string $key, string $content)
    {
        if ($this->hasKey($key))
            throw new \LogicException("Key: $key does already exist");

        $this->content[] = [
            self::KEY => $key,
            self::RESPONSE => preg_replace('/\s+/S', " ", $content),
            self::EXPIRY_TIME => ($this->lifetime != 0) ? (time() + $this->lifetime) : 0,
        ];

        $this->save();
    }

    public function delete(string $key)
    {
        if (!$this->hasKey($key))
            throw new \LogicException("Key: $key does not exist");

        $aryKey = array_search($key, array_column($this->content, self::KEY));

        unset($this->content[$aryKey]);
        $this->save();
    }

    public function parseLine(array $itemChunk)
    {
        $this->content[] = $itemChunk;
        $this->count++;
    }

    protected function save()
    {
        $jsonEncoded = json_encode(array_values($this->content), JSON_PRETTY_PRINT);
        file_put_contents($this->cachePath, $jsonEncoded);
        $this->initialize();
    }

}
