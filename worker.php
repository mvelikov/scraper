<?php

require 'simple_html_dom/simple_html_dom.php';

$host = 'http://luxdiets.com/';

$urlsArray = array(
    $host => 1,
);

$database = array();

var_dump($urlsArray, $host);

$mongo = new Mongo();

$db = $mongo->scraper;

$collection = $db->sites;

// foreach($urlsArray as $url => $dummy) {
while (list($url, $dummy) = each($urlsArray)) {
    $html = file_get_html($url);
    if (!is_object($html)) continue;
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
    $elements['host'] = $host;
    $elements['type'] = 'page';


    $collection->insert($elements);
    // $database[] = $elements;
    usleep(1000);
}





echo 'done';
