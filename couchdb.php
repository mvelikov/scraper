<?php


function curl($url, $data = array(), $type = 'GET') {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $response = curl_exec($ch);
    if(!$response) {
        throw new Exception("Error responsing curl request!", 1);
        die();
    }
    return $response;
}

$localhost = 'http://localhost:5984/';
