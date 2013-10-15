<?php

require 'simple_html_dom/simple_html_dom.php';

$urlsArray = array(
    'http://mihailvelikov.eu/' => 1,
);

$mongo = new Mongo();
$db = $mongo->scraper;
$collection = $db->list;

$database = array();

$hostComponents = parse_url($urlsArray[0]);
$host = 'http://mihailvelikov.eu';
$cnt = 0;
// foreach($urlsArray as $url => $dummy) {
while (list($url, $dummy) = each($urlsArray)) {
    $html = file_get_html($url);

    $elements = array();

    foreach ($html->find('a') as $a) {

        $parsedUrlComponents = parse_url($a->href);
        
        $parsedUrl = $parsedUrlComponents['scheme'] . '://' . $parsedUrlComponents['host'];
        
        if ($parsedUrl == $host) {
            $urlsArray[$a->href] = 1;
        }
    }
    foreach ($html->find('h1') as $h1) {
        $elements['h1'][] = $h1->text;
    }
    foreach ($html->find('meta') as $meta) {
        if ($meta->name == 'description') {
            $elements['meta_description'] = $meta->value;
        }

        if ($meta->name == 'description') {
            $elements['meta_description'] = $meta->value;
        }
    }
}
var_dump($urlsArray);
