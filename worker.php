<?php

require 'simple_html_dom/simple_html_dom.php';

$host = 'http://rmsoft.eu/';

$urlsArray = array(
    $host => 1,
);

$database = array();

var_dump($urlsArray, $host);

// foreach($urlsArray as $url => $dummy) {
while (list($url, $dummy) = each($urlsArray)) {
    $html = file_get_html($url);

    $elements = array();

    foreach ($html->find('a') as $a) {

        $parsedUrlComponents = parse_url($a->href);
        
        if (!empty($parsedUrlComponents['scheme']) && !empty($parsedUrlComponents['host'])) {
            $parsedUrl = $parsedUrlComponents['scheme'] . '://' . $parsedUrlComponents['host'] . '/';
        
            if ($parsedUrl == $host) {
                $urlsArray[$a->href] = 1;
            }
        }
    }
    foreach ($html->find('h1') as $h1) {
        $elements['h1'][] = $h1->plaintext;
    }
    foreach ($html->find('meta') as $meta) {
        if ($meta->name == 'description') {
            $elements['meta_description'] = $meta->value;
        }

        if ($meta->name == 'description') {
            $elements['meta_description'] = $meta->value;
        }
    }
    foreach ($html->find('title') as $title) {
        $elements['title'] = $title->plaintext;
    }
    foreach ($html->find('body') as $key => $body) {
        $elements['words'] = str_word_count($body->plaintext);
    }

    $elements['url'] = $url;

    $database[sha1($url)] = $elements;
    usleep(400);
}

$dsn = 'mysql:dbname=scraper;host=localhost;port=8889';
$user = 'root';
$password = 'root';

try {
    $dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$sqlInsertSite = sprintf("INSERT IGNORE INTO sites (name) VALUES('%s');", $host);

$dbh->exec($sqlInsertSite);

$siteId = $siteExists = false;
$fetchSiteSql = sprintf("SELECT site_id FROM sites WHERE name = '%s' LIMIT 1", $host);
var_dump($fetchSiteSql);
foreach ($dbh->query($fetchSiteSql) as $row) {
    $siteExists = true;
    $siteId = $row['site_id'];
}


var_dump($siteId);

echo 'done';
