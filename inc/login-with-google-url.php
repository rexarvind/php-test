<?php

require_once('vendor/autoload.php');
require_once('inc/config.php');

$client_id = defined('GOOGLE_CLIENT_ID') ? GOOGLE_CLIENT_ID : '';
$client_secret = defined('GOOGLE_CLIENT_SECRET') ? GOOGLE_CLIENT_SECRET : '';
$client_callback_url = defined('GOOGLE_CALLBACK_URL') ? GOOGLE_CALLBACK_URL : '';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($client_callback_url);
$client->addScope('email');
$client->addScope('profile');

$login_with_google_url = $client->createAuthUrl();
