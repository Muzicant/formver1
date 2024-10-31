<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$source = $_SERVER['SERVER_NAME'];


function getUTM($key, $fallback = '', $cookieFallback = '', $cookieName = '', $getAlt = '') {
    
    if (!empty($_GET[$key])) {
        $value = htmlspecialchars($_GET[$key], ENT_QUOTES);
        setcookie($cookieName ?: $key, $value, time() + 2592000, "/"); 
    } elseif (!empty($_GET[$getAlt])) { 
        $value = htmlspecialchars($_GET[$getAlt], ENT_QUOTES);
        setcookie($cookieName ?: $key, $value, time() + 2592000, "/");
    } elseif (!empty($_COOKIE[$cookieName ?: $key])) { 
        $value = htmlspecialchars($_COOKIE[$cookieName ?: $key], ENT_QUOTES);
    } else {
        $value = $fallback; 
    }
    return $value;
}


$utm_source = getUTM('utm_source', 'organic', 'utm_source','utm_source','sub_id_1' );
$utm_campaign = getUTM('utm_campaign', '', 'utm_campaign', 'utm_campaign', 'sub_id_2');
$utm_medium = getUTM('utm_medium', '', 'utm_medium', 'utm_medium', 'sub_id_3');
$utm_content = getUTM('utm_content', '', 'utm_content', 'utm_content', 'sub_id_4');
$utm_term = getUTM('utm_term', '', 'utm_term', 'utm_term', 'sub_id_5');
$sub10 = !empty($_GET['sub_id_10']) ? '&sub_id_10=' . htmlspecialchars($_GET['sub_id_10'], ENT_QUOTES) :
    (!empty($_COOKIE['sub_id_10']) ? '&sub_id_10=' . htmlspecialchars($_COOKIE['sub_id_10'], ENT_QUOTES) : '');

$tail = "utm_source={$utm_source}&source={$source}&utm_campaign={$utm_campaign}&utm_medium={$utm_medium}&utm_content={$utm_content}&utm_term={$utm_term}{$sub10}";

function setUserIPAndAgent() {
    $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'] ??
        $_SERVER['HTTP_X_REAL_IP'] ??
        $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    return compact('userIP', 'userAgent');
}

$userData = setUserIPAndAgent();
$userIP = $userData['userIP'];
$userAgent = $userData['userAgent'];

?>
