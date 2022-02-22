# Guzzcache
A in-File JSON-Cache for storing Guzzle HTTP Responses from REST-APIs

## Installation

Install using composer:

```
    composer require bookking3000/guzzcache
```

## Autoloading

Please include the following to autoload Classes

```
    require_once __DIR__.'/vendor/bookking3000/guzzcache/autoload.php';
```

## Usage

```
    //Initialize Json-File for Caching.
    $jsonCache = new JsonCache();
    $jsonCache->setCachePath($this->cachePath);

    //Initialize GuzzCache and set the Cache to the Cache initialized before.
    $guzzCache = new GuzzCache();
    $guzzCache->setCache($jsonCache);

    //If your request was already sent, this method will retrieve it from the local in-File Cache
    $content = $guzzCache->httpQuery("https://jsonplaceholder.typicode.com/todos/1", "GET");
```

## Lifetime

Default Lifetime is 30 days / 2592000 Seconds.

You can change it as follows:

```
    $jsonCache = new JsonCache();
    $jsonCache->setCachePath($this->cachePath);
    $jsonCache->setLifetime(60); //60 Seconds
```

To disable Lifetime, set Lifetime to 0.

```
    $jsonCache = new JsonCache();
    $jsonCache->setCachePath($this->cachePath);
    $jsonCache->setLifetime(0);
```

## Debug

Setting Debug to true, will log all Requests. By Default only Requests with non 2xx Response Status-Codes are logged.

The Log is stored in the logs-Folder in the Library-Directory itself.

```
    $guzzCache = new GuzzCache();
    $guzzCache->setDebug(true);
```

# Changelog

## 2022-02-15

- Added Expiry-Time to Cache
