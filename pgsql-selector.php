<?php

require_once 'pgsql.php';

$host = 'http://mihailvelikov.eu/';
$fetchSiteSql = sprintf("SELECT site_id FROM sites WHERE name = '%s' LIMIT 1", $host);


$start = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $res = $dbh->query($fetchSiteSql);
    $res->fetch();
}

echo microtime(true) - $start;