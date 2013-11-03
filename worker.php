<?php

require_once 'crawler.php';
$dsn = 'mysql:dbname=scraper;host=localhost;port=8889';
$user = 'root';
$password = 'root';

try {
    $dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$start = microtime(true);
for ($i = 0; $i < 1; $i++) {

    $insertSiteSql = $dbh->prepare("INSERT IGNORE INTO sites (name) VALUES(:host);");

    $insertSiteSql->execute(array(':host' => $host));

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
                    ':type' => '1',
                    ':page_id' => $lastInsertedPageId,
                ));
            }
        }
    }

}

echo microtime(true) - $start;
echo 'done';
