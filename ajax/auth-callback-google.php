<?php

require_once '../vendor/autoload.php';
require_once '../inc/config.php';

$client_id = defined('GOOGLE_CLIENT_ID') ? GOOGLE_CLIENT_ID : '';
$client_secret = defined('GOOGLE_CLIENT_SECRET') ? GOOGLE_CLIENT_SECRET : '';
$client_callback_url = defined('GOOGLE_CALLBACK_URL') ? GOOGLE_CALLBACK_URL : '';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($client_callback_url);
$client->addScope('email');
$client->addScope('profile');

// authenticate code from Google OAuth Flow
if ( isset($_GET['code']) ) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if( isset($token['access_token']) ){
        $client->setAccessToken($token['access_token']);

        // get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email =  $google_account_info->email;
        $name =  $google_account_info->name;
        $avatar = $google_account_info->picture;

        ?>
        <h1><?php echo $name; ?></h1>
        <p>email: <?php echo $email; ?></p>
        <img src="<?php echo $avatar; ?>" style="width:60px;height:60px;">
        <a href="/">Back to home</a>
        <?php
    } else {
        header('Location: ../errors/500.php');
        exit();
    }
} else {
    header('Location: ../errors/404.php');
    exit();
}