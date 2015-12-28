<?php

require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';




//$openid = $_SESSION['openid'];

//$access_token = $_SESSION['access_token'];
$token =  apcfetch("ACCESS_TOKEN");
$access_token = $token->access_token;
$mediaId = param('headerMediaId');

//$url = "https://qyapi.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaId";
$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaId";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_NOBODY, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    $info = curl_getinfo($curl);
    curl_close($curl);

print_r($info);



?>
