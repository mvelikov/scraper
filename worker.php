<?php

require_once 'crawler.php';

$mongo = new Mongo();

$db = $mongo->scraper;

$collection = $db->sites;

$start = microtime(true);
for ($i = 0; $i < 1; $i++ ) {
    $elements['_id'] = new MongoId();
    $collection->insert($elements
    , array(
        'fsync' => true
    ));
}

echo microtime(true) - $start;
// echo 'done';
