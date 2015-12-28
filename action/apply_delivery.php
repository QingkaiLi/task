<?php
// 申请快递员资格
require_once '../common/common.php';

$openid = $_SESSION['openid'];
$account = Account::getAccount($openid);
$access_token = $_SESSION['access_token'];
if ($account == null) {
	$weixinAPI = new WeixinAPI();
	$userInfo = $weixinAPI->getUserInfo($access_token['access_token'], $openid);
	Account::createAccount($openid, $userInfo['nickname']);
}

//$icon = uploadFile('icon', "icon");

$icon = param("icon");
if ($icon == null)  {
	operation_fail("头像不能为空");
}
$address = param("address");
$fullname = param("fullname");

$idcard = param('idcard');

$contact = param('contact');
if ($contact == null)
	$contact = $fullname;
$contact_phone = param("contactPhone");

/*
$card_pic1 = uploadFile('cardPic1', 'cardPic1');
$card_pic2 = uploadFile('cardPic2', 'cardPic2');
$card_pic3 = uploadFile('cardPic', 'cardPic3');
$card_pic = $card_pic1 . "," . $card_pic2 . "," . $card_pic3;*/
$card_pic1 = param("cardPic1");
$card_pic2 = param("cardPic2");
$card_pic3 = param("cardPic3");
$card_pic = $card_pic1 . "," . $card_pic2 . "," . $card_pic3;

//$card_pic = param("cardPic");

$inviter = param('inviter');

$accountExtra = AccountExtra::getInfo($openid);
if ($accountExtra == null) {
	$r = AccountExtra::applyDelivery($openid, $icon, $address, $fullname, $idcard, $contact, $contact_phone, 
	$card_pic, $inviter);
	if ($r) {
		$extra = AccountExtra::getInfo($openid);
		if ($extra != null) {
			$_SESSION['account_extra'] = $extra;	
		}
		json_put("result", $r);
		json_output();
	} else {
		operation_fail("申请失败，请重试");
	}
} else {
	if ($accountExtra['status'] == AccountExtra::STATUS_APPLYING) {
		operation_fail("你的快递资格已在申请中，请耐心等待审核");
	} else if ($accountExtra['status'] == AccountExtra::STATUS_ACCEPT) {
		operation_fail("你已经有快递资格了");
	}
	$r = AccountExtra::updateDelivery($openid, $icon, $address, $fullname, $idcard, $contact, $contact_phone, $card_pic, $inviter);
	if ($r) {
		json_put("result", "申请成功");
		json_output();
	} else {
		operation_fail("申请失败，请重试");
	}
}



?>
