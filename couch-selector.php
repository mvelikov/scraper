<?php

require_once 'couchdb.php';




$start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    $doc = curl($localhost . 'scraper/13d1517b3481f04b6fb4542a4f000b8b');
    // foreach ($cursor as $doc)
        var_dump(json_decode($doc));

}

echo microtime(true) - $start;