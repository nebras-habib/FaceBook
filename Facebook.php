<?php

class Facebook {

    private $authUrl;
    private $appId;
    private $appSecret;
    private $redirectUrl;
    private $state;

    public function __construct($authUrl, $appId, $appSecret, $redirectUrl) {
        $this->authUrl = $authUrl;
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->redirectUrl = $redirectUrl;
        $this->state = md5(uniqid(rand(), TRUE));
    }

    public function getLoginUrl($responseType, $scope) {
        $url = $this->authUrl . 'client_id=' . $this->appId .
                '&redirect_uri=' . $this->redirectUrl .
                '&state=' . $this->state .
                '&response_type=' . $responseType .
                '&scope=' . $scope;

        return $url;
    }

    public function getState() {
        return $this->state;
    }

    public function getShortLivedToken($url, $code) {
        $tokenUrl = $url
                . "client_id=" . $this->appId
                . "&redirect_uri=" . $this->redirectUrl
                . "&client_secret=" . $this->appSecret
                . "&code=" . $code;
        $result = $this->getResponse($tokenUrl);
        return json_decode($result)->access_token;
    }

    public function getLongLivedToken($url, $shortLivedToken) {
        $tokenUrl = $url
                . "grant_type=fb_exchange_token"
                . "&client_id=" . $this->appId
                . "&client_secret=" . $this->appSecret
                . "&fb_exchange_token=" . $shortLivedToken;
        $result = $this->getResponse($tokenUrl);
        return json_decode($result)->access_token;
    }

    public function getUserInfo($url, $longLivedToken) {
        $userUrl = $url . $longLivedToken;
        $result = $this->getResponse($userUrl);
        return json_decode($result);
    }

    public function getUserPictureUrl($url, $userId) {
        $pictureUrl = $url . $userId
                . "/picture"
                . "?redirect=0"
                . "&type=large";
        $result = $this->getResponse($pictureUrl);
        return json_decode($result)->data->url;
    }

    public function getResponse($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    // it is from
    //https://developers.facebook.com/docs/games/gamesonfacebook/login#parsingsr

    public function parseSignedRequest($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = $this->appSecret; // Use your app secret here
        // decode the data
        $sig = base64UrlDecode($encoded_sig);
        $data = json_decode(base64UrlDecode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            //error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    public function base64UrlDecode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

}

?>