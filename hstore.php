<?php

include '../PHPG/phpg_utils.php';
require_once 'crawler.php';


require_once 'pgsql.php';
$elements['h1']['primary'] = 'Lorem ipsum';
var_dump($elements);

$start = microtime(true);
for ($i = 0; $i < 1; $i++) {
    
    // $hstore_array = PHPG_Utils::hstoreFromPhp($elements, true);
    $insertSiteSql = $dbh->prepare("INSERT INTO jsontest (data) VALUES('" . json_encode($elements) . "');");

    $res = $insertSiteSql->execute();
}

echo microtime(true) - $start;
echo 'done';
