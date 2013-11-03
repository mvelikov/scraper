<?php

require_once 'couchdb.php';



// foreach($urlsArray as $url => $dummy) {
require_once 'crawler.php';

$start = microtime(true);
for ($i = 0; $i < 10; $i++ ) {
    curl($localhost . 'scraper', $elements, 'POST');
}

echo microtime(true) - $start;
// echo 'done';
