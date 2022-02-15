<?php

namespace bookking3000\Guzzcache;

use bookking3000\Guzzcache\FileSystem\InFileCache;
use bookking3000\Guzzcache\QA\Logger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzCache
{

    public const NAME = 'GuzzCache';
    protected bool $debug = false;
    protected InFileCache $cache;

    /**
     * @throws GuzzleException
     */
    public function httpQuery($uri, $method, $options = []): string
    {
        $cacheKey = $this->getHashKey($uri, $method, $options);

        if (isset($this->cache) && $this->cache->hasKey($cacheKey)) {
            if ($this->cache->isExpired($cacheKey)) {
                Logger::writeLog('Lifetime expired for ' . $cacheKey);
                $this->cache->delete($cacheKey);
            } else {
                return $this->cache->readResponse($cacheKey);
            }
        }

        $client = new Client();
        $result = $client->request($method, $uri, $options);

        if (!$this->isHttpSuccess($result->getStatusCode())) {
            Logger::writeLog(
                'HTTP Query::' . json_encode([$uri, $method, $options, $result->getStatusCode()]),
                Logger::ERROR
            );
        } elseif ($this->debug) {
            Logger::writeLog(
                'HTTP Query::' . json_encode([$uri, $method, $options, $result->getStatusCode()]),
                Logger::DEBUG
            );
        }

        $content = $result->getBody()->getContents();

        if ($this->isHttpSuccess($result->getStatusCode()) && isset($this->cache)) {
            $this->cache->write($cacheKey, $content);
        }

        return $content;
    }

    public function setCache(InFileCache $cache): void
    {
        $this->cache = $cache;
    }

    public function getHashKey($uri, $method,$options): string
    {
        return sha1(serialize([$uri, $method,$options]));
    }

    private function isHttpSuccess($statusCode): bool
    {
        return 2 == (int)floor($statusCode / 100);
    }

    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

}
