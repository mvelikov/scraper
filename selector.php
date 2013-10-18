<?php

$host = 'http://rmsoft.eu/';
$mongo = new Mongo();

$db = $mongo->scraper;

$collection = $db->sites;

$start = microtime(true);
for ($i = 0; $i < 100000; $i++ ) {

    $collection->find(array(
        '_id' => sha1($host),
    ));
}

echo microtime(true) - $start;
