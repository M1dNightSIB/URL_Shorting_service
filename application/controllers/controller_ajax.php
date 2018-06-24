<?php
include '../models/stupid_model.php';

$domen = 'practic.site';
$newUrl = NULL;
$dataBase = NULL;

function sendUrl($url=null)
{
    echo $url;
}

function getShortName($url, $length)
{
    $tmp_len = $length / 2;
    $hash = md5($url);
    $hash_len = strlen($hash);
    $half1 = substr($hash, 0, $tmp_len);
    $half2 = substr($hash, $hash_len - $tmp_len, $hash_len - 1);
    $shortUrl = "$half1$half2";
    
    return $shortUrl;
}

if($_POST['browser'] == NULL){
    echo "Can't determine browser";
}else if($_POST['name'] == NULL){
    echo "No links";
}else{
    $length = 7;

    $url = $_POST['name'];
    $shortUrl = getShortName($url, $length);

    $dataBase = new Model_Main();
    if($key = $dataBase->getKey($url)){
        $shortUrl = "$domen/$key";
    }else{
        $dataBase->insertUrl($url, $shortUrl);
        $shortUrl = "$domen/$shortUrl";
    }
    sendUrl($shortUrl);
    exit();
}

