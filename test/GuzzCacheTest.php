<?php


use bookking3000\Guzzcache\FileSystem\JsonCache;
use bookking3000\Guzzcache\GuzzCache;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

class GuzzCacheTest extends TestCase
{
    private $cachePath =
        __DIR__ .
        DIRECTORY_SEPARATOR . 'cache' .
        DIRECTORY_SEPARATOR .
        GuzzCache::NAME . '.json';

    public function testSetCache()
    {
        $jsonCache = new JsonCache();
        $jsonCache->setCachePath($this->cachePath);

        self::assertFileExists($this->cachePath);
        self::assertFileIsReadable($this->cachePath);
        self::assertFileIsWritable($this->cachePath);

        $guzzCache = new GuzzCache();
        $guzzCache->setCache($jsonCache);

        self::assertObjectHasAttribute('cache', $guzzCache);
    }

    public function testHttpQuery()
    {
        $jsonCache = new JsonCache();
        $jsonCache->setCachePath($this->cachePath);
        $jsonCache->setLifetime(0);

        $guzzCache = new GuzzCache();
        $guzzCache->setCache($jsonCache);
        $guzzCache->setDebug(false);

        for ($i = 1; $i <= 35; $i++) {
            $content = $guzzCache->httpQuery("https://jsonplaceholder.typicode.com/todos/$i", "GET");
            self::assertNotNull($content);
        }

        $jsonCache->delete('630d94090acc37ebafcd2360e69bcac1f325990b');
        $jsonCache->delete('c617f312b9424ea02907f293ce2bade866644262');

        self::assertFalse($jsonCache->hasKey('630d94090acc37ebafcd2360e69bcac1f325990b'));
        self::assertFalse($jsonCache->hasKey('c617f312b9424ea02907f293ce2bade866644262'));

    }
}
