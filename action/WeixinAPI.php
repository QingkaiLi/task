<?php
class WeixinAPI {
	public function getOpenid($code) {
		// 通过授权码获取OPENID
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".
			 APPID ."&secret=". APPSECRET . "&code=" . $code . "&grant_type=authorization_code";
		$json = curl_read_json($url);
		return $json;
	}
	public function getUserInfo($access_token, $openid) {
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=". $access_token ."&openid=". $openid;
		$json = curl_read_json($url);
		return $json;
	}
}


?>
