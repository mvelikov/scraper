<?php

require_once 'crawler.php';


$dsn = 'pgsql:dbname=mydb;host=localhost;port=5432';
$user = 'macosx';
$password = 'p0diuM';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$start = microtime(true);
for ($i = 0; $i < 1; $i++) {

    $insertSiteSql = $dbh->prepare("INSERT INTO sites (name) VALUES(:host);");

    var_dump($insertSiteSql->execute(array(':host' => $host)));

    $siteId = $siteExists = false;
    $fetchSiteSql = sprintf("SELECT site_id FROM sites WHERE name = '%s' LIMIT 1", $host);

    foreach ($dbh->query($fetchSiteSql) as $row) {
        $siteExists = true;
        $siteId = $row['site_id'];
    }

    foreach ($database as $key => $page) {
        $insertPageSql = $dbh->prepare("INSERT INTO pages (url, words, site_id) VALUES (:url, :words, :site_id);");
        $result = $insertPageSql->execute(array(
            ':url' => $page['url'],
            ':words' => $page['words'],
            ':site_id' => $siteId
        ));
        var_dump($result);
        if ($result) {
            
            $lastInsertedPageId = $dbh->lastInsertId('pages_page_id_seq');

            $insertMetaSql = $dbh->prepare("INSERT INTO meta (description, keywords, title, page_id) 
            VALUES (:description, :keywords, :title, :page_id)");
            

            $insertMetaSql->execute(array(
                ':description' => $page['meta_description'],
                ':keywords' => $page['meta_keywords'],
                ':title' => $page['title'],
                ':page_id' => $lastInsertedPageId
            ));

            foreach ($page['h1'] as $key => $h1) {
                $insertHeadingSql = $dbh->prepare("INSERT INTO headings (txt, type, page_id) 
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
