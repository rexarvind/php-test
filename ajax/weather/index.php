<?php

date_default_timezone_set('Asia/Kolkata');
header('Content-Type: application/json; charset=UTF-8');

require_once('../../inc/config.php');

$token = defined('OPEN_WEATHER_MAP_KEY') ? OPEN_WEATHER_MAP_KEY : '';
$city_id = ( isset($_GET['id']) && !empty($_GET['id']) ) ? $_GET['id'] : '1264733';
$file = $city_id . '.json';
$url = 'https://api.openweathermap.org/data/2.5/weather?appid='. $token .'&id='. $city_id . '&units=metric&';

function getLiveWeather($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $res = curl_exec($ch);
    $error = curl_errno($ch);
    $errorMsg = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['response'=> $res, 'httpCode'=> $httpCode, 'error'=> $error, 'errorMsg'=> $errorMsg, 'url'=> $url];
}

function saveWeather($response, $file){
    $json = json_decode($response, true);
    $json['time'] = time();
    $json['updated_at'] = date('h:i:s a, d M');
    $json_string = json_encode($json);
    $isSaved = file_put_contents($file, $json_string);
    return $isSaved ? $json_string : false;
}

function sendError($msg, $error = '', $code = 400, $url = ''){
    echo json_encode(['status'=> false, 'message'=> $msg, 'error'=> $error, 'code'=> $code, 'url'=> $url]);
}

function giveWeather($file){
    $data = file_get_contents($file);
    echo $data;
}

function checkApiResponse($data, $file){
    if( $data['error'] || $data['httpCode'] !== 200 ){
        sendError($data['errorMsg'], $data['error'], $data['httpCode']);
    } else {
        $hasSaved = saveWeather($data['response'], $file);
        if($hasSaved !== false){
            giveWeather($file);
        } else {
            sendError('Can not save to file.');
        }
    }
}

if( file_exists($file) ){
    $json_string = file_get_contents($file);
    $json = json_decode($json_string, true);
    if( is_array($json) && isset($json['time']) && (intval($json['time']) + 110) > time() ) {
        giveWeather($file);
    } else {
        $data = getLiveWeather($url);
        checkApiResponse($data, $file);
    }
} else {
    $data = getLiveWeather($url);
    checkApiResponse($data, $file);
}

exit();
