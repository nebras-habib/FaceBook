<?php

require './Facebook.php';
require './FacebookConfigure.php';
require './Database.php';
require './DatabaseConfigure.php';

if (isset($_POST['signed_request '])) {
    $facebook = new Facebook(FacebookConfigure::$authUrl, FacebookConfigure::$appId, FacebookConfigure::$appSecret, FacebookConfigure::$redirectUrl);
    $signedRequest = $facebook->parseSignedRequest($_POST['signed_request ']);
    if ($signedRequest !== NULL) {
        $database = new Database(DatabaseConfigure::$dsn, DatabaseConfigure::$userName, DatabaseConfigure::$password);
        $database->deauthUser($signedRequest->user_id);
    }
}
