<?php

require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';
//url
$code = param("code");

if ($code == null) {
	operation_fail("登陆失败");
}

$weixinAPI = new WeixinAPI();
$json = $weixinAPI->getOpenid($code);

session_start();
// 记录用户信息
$_SESSION['openid']=$json['openid'];
$_SESSION['access_token']=$json;
$openid = $json['openid'];

$home_url = '../index.php';
$account = Account::getAccount($json['openid']);
if ($account != null) {
	$_SESSION['account'] = $account;
	$extra = AccountExtra::getInfo($json['openid']);
	if ($extra != null) {
		$_SESSION['account_extra'] = $extra;	
	}
} else {
	$weixinAPI = new WeixinAPI();
	$userInfo = $weixinAPI->getUserInfo($json['access_token'], $openid);
	Account::createAccount($openid, $userInfo['nickname']);
	$account = Account::getAccount($json['openid']);
	$_SESSION['account'] = $account;
}

//$home_url = '../h5/publish_task_page.php';
header('Location: '.$home_url);

json_put("session", $_SESSION['access_token']);
json_output();

?>

