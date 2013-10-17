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
        $elements['meta_description'] = $elements['meta_keywords'] = 'NULL';
        if ($meta->name == 'description') {
            $elements['meta_description'] = $meta->value;
        }

        if ($meta->name == 'keywords') {
            $elements['meta_keywords'] = $meta->value;
        }
    }
    $elements['title'] = 'NULL';
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

$insertSiteSql = sprintf("INSERT IGNORE INTO sites (name) VALUES('%s');", $host);

$dbh->exec($insertSiteSql);

$siteId = $siteExists = false;
$fetchSiteSql = sprintf("SELECT site_id FROM sites WHERE name = '%s' LIMIT 1", $host);

foreach ($dbh->query($fetchSiteSql) as $row) {
    $siteExists = true;
    $siteId = $row['site_id'];
}

foreach ($database as $key => $page) {
    $insertPageSql = $dbh->prepare("INSERT INTO pages (url, words, site_id) VALUES (:url, :words, :site_id);");
        
    if ($insertPageSql->execute(array(
            ':url' => $page['url'],
            ':words' => $page['words'],
            ':site_id' => $siteId
        ))) {
        var_dump($page);
        $lastInsertedPageId = $dbh->lastInsertId();
       
        $insertMetaSql = $dbh->prepare("INSERT INTO meta (description, keywords, title, page_id) 
        VALUES (:description, :keywords, :title, :page_id)");
        

        $insertMetaSql->execute(array(
            ':description' => $page['meta_description'],
            ':keywords' => $page['meta_keywords'],
            ':title' => $page['title'],
            ':page_id' => $lastInsertedPageId
        ));

        foreach ($page['h1'] as $key => $h1) {
            $insertHeadingSql = $dbh->prepare("INSERT INTO headings (text, type, page_id) 
                VALUES (:text, :type, :page_id)"); 

            $insertHeadingSql->execute(array(
                ':text' => $h1,
                ':type' => 'h1',
                ':page_id' => $lastInsertedPageId,
            ));
        }

       
    }
}


echo 'done';
