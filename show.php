<?php

session_start();
require './Facebook.php';
require './FacebookConfigure.php';
require './Database.php';
require './DatabaseConfigure.php';

if (isset($_GET['code']) && isset($_GET['state'])) {

    if ($_SESSION['state'] != $_GET['state']) {
        // error message
        echo 'invalid request !!';
        exit();
    }
    $facebook = new Facebook(FacebookConfigure::$authUrl, FacebookConfigure::$appId, FacebookConfigure::$appSecret, FacebookConfigure::$redirectUrl);
    $shortLivedToken = $facebook->getShortLivedToken("https://graph.facebook.com/oauth/access_token?", $_GET['code']);
    $longLivedToken = $facebook->getLongLivedToken("https://graph.facebook.com/oauth/access_token?", $shortLivedToken);
    $userInfo = $facebook->getUserInfo("https://graph.facebook.com/me?access_token=", $longLivedToken);

    echo $userInfo->name;
    echo "<br>";
    echo "<br>";

    $userPictureUrl = $facebook->getUserPictureUrl("https://graph.facebook.com/", $userInfo->id);

    echo "<img src='" . $userPictureUrl . "' width='200' height='200'/>";
    echo "<br>";
    echo "<br>";
    echo "<a href='Logout.php'>Log Out</a>";

    $database = new Database(DatabaseConfigure::$dsn, DatabaseConfigure::$userName, DatabaseConfigure::$password);
    $database->insertOrUpdateUser($userInfo->id, $userInfo->name, $longLivedToken);
} else {
    // error message
    echo 'invalid request !!';
}