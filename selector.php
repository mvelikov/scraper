<?php

$dsn = 'mysql:dbname=scraper;host=localhost;port=8889';
$user = 'root';
$password = 'root';

try {
    $dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$host = 'http://rmsoft.eu/';
$fetchSiteSql = sprintf("SELECT site_id FROM sites WHERE name = '%s' LIMIT 1", $host);


$start = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $res = $dbh->query($fetchSiteSql);
    $res->fetch();
}

echo microtime(true) - $start;