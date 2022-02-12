# Guzzcache
A in-File JSON-Cache for storing Guzzle HTTP Responses from REST-APIs

## Installation

WIP

## Usage

```
    //Initialize Json-File for Caching.
    $jsonCache = new JsonCache();
    $jsonCache->setCachePath($this->cachePath);

    //Initialize GuzzCache and set the Cache to the Cache initialized before.
    $guzzCache = new GuzzCache();
    $guzzCache->setCache($jsonCache);

    //If your request was already sent, this method will retrieve it from the local in-File Cache
    $content = $guzzCache->httpQuery("https://jsonplaceholder.typicode.com/todos/$i", "GET");
```

## Debug

Setting Debug to true, will log all Requests. By Default only Requests with non 2xx Response Status-Codes are logged.

The Log is stored in the logs-Folder in the Library-Directory itself.

```
    $guzzCache = new GuzzCache();
    $guzzCache->setDebug(true);
```
