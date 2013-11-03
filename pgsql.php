<?php

$dsn = 'pgsql:dbname=mydb;host=localhost;port=5432';
$user = 'macosx';
$password = 'p0diuM';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
