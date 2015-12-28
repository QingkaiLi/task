<?php
// 获取验证码
require_once '../common/common.php';

$openid = $_SESSION['openid'];

$phone = param("phone");
if ($phone == null || strlen($phone) < 8 ) {
	operation_fail("手机号码不能为空或者位数不对");
}

$code = param("code");

if ($code == null || strlen($code) < 4 ) {
	operation_fail("手机号码不能为空或者位数不对");
}

$r = VerifyCodeModel::getCodeByPhone($phone);
if ($r == null || $code!=$r["code"]) {
	operation_fail("验证码不对");
}
Account::updatePhone($phone, $openid);
VerifyCodeModel::deleteVerifyCode($phone);

$account = Account::getAccount($openid);
$_SESSION['account'] = $account;

json_put("result", "认证成功");
json_output();
?>

