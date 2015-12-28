<?php
// 获取验证码
require_once '../common/common.php';
require_once '../sms/SendTemplateSMS.php';

session_start();

//$code = param("code", '');
//if ($code != $_SESSION["code"]) {
//	operation_fail("验证码验证失败:". $_SESSION["code"]);
//}

$phone = param("phone");
if ($phone == null || strlen($phone) < 8 ) {
	operation_fail("手机号码不能为空或者位数不对");
}

$r = VerifyCodeModel::getCodeByPhone($phone);
if ($r != null) {
	// 如果已有验证码，并且还未超过1分钟
	if ($r['status'] == 0 && strtotime($r[time]) + 60 > time()) {
		operation_fail("验证码请间隔1分钟再重试");
	}
}

$code = rand(1000,9999);
$timeout = 1;

//json_put("code", $code);
$result = sendTemplateSMS($phone, array($code, $timeout), "1");
if ($result == FALSE) {
	operation_fail("发送验证码失败，请重试");
} else {
	$r = VerifyCodeModel::updateOrInsertVerifyCode($phone, $code);
	if ($r==FALSE) {
		operation_fail("发送验证码失败，请重试");
	}
}
json_put("result", "验证码已下发，请查收");
json_output();
?>

