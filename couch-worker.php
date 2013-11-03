<?php

require_once 'couchdb.php';
require 'simple_html_dom/simple_html_dom.php';

$host = 'http://mihailvelikov.eu/';

$urlsArray = array(
    $host => 1,
);

$database = array();


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
    break;
}


$start = microtime(true);
for ($i = 0; $i < 10; $i++ ) {
    curl($localhost . 'scraper', $elements, 'POST');
}

echo microtime(true) - $start;
// echo 'done';