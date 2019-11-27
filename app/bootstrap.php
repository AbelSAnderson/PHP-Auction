<?php

require_once(__DIR__ . '/Config/config.php');
require_once(__DIR__ . '/Lib/Functions.php');

require_once(__DIR__ . '/../vendor/autoload.php');

$session = new \App\Lib\Session();

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(CLIENT_ID, CLIENT_SECRET)
);

$apiContext->setConfig(['mode' => PAYPAL_MODE]);