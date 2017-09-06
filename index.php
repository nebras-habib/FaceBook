<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Facebook App</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <?php
        require './Facebook.php';
        require './FacebookConfigure.php';
        $facebook = new Facebook(FacebookConfigure::$authUrl, FacebookConfigure::$appId, FacebookConfigure::$appSecret, FacebookConfigure::$redirectUrl);
        $_SESSION['state'] = $facebook->getState();
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <br>
                    <a href="<?php echo $facebook->getLoginUrl('code', 'public_profile'); ?>"><button>Login with Facebook</button></a>
                </div>
            </div>
        </div>
    </body>
</html>
