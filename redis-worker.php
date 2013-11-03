<?php

require '../predis/autoload.php';

Predis\Autoloader::register();

$redis = new Predis\Client();


require_once 'crawler.php';


$start = microtime(true);
for ($i = 0; $i < 10; $i++ ) {
    $redis->hmset('key-' . $i, 'test',  $elements);
}

echo microtime(true) - $start;
