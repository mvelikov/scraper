<?php

$mongo = new Mongo();

$db = $mongo->scraper;

$collection = $db->sites;

$start = microtime(true);
for ($i = 0; $i < 100000; $i++ ) {

    $cursor = $collection->find(array(
        '_id' => new MongoId('5262c67d09a992943e000012'),
    ));

    foreach ($cursor as $doc)
        var_dump($doc);
}

echo microtime(true) - $start;
