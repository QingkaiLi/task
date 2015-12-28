<?php
require_once '../common/common.php';

$openId = $_SESSION['openid'];

$publisher = param('user');
$phone = param('phone');
//$title = param('title');
$desc = param('desc');
$title = mb_substr($desc,0,10,'utf-8');
//$startTime = param('startTime');
$endTime = param('endTime');
$reward = param('tip');
$address = param('address');
//$verifyCode = param('verifyCode');
//$fromAddress = param('fromAddress');

$lng = param('lng');//经度
$lat = param('lat');//续度

if ($phone == null) {
	operation_fail('发布任务手机号不能为空');
}
//if ($title == null || strlen($title) <= 0) {
//	operation_fail('任务标题不能为空');
//}
if ($desc == null || strlen($desc) <= 0) {
	operation_fail('发布任务需求不能为空');
} 

$now = date("Y-m-d H:i:s", time());
//date_default_timezone_set('PRC');
if ($endTime != null && strtotime($now) > strtotime($endTime)) {
	operation_fail("任务的送达时间" . $endTime . "不能小于当前时间".$now);
} else if ($endTime == null) {
	$endTime = $now;
}
//if ($verifyCode == null) {
//	operation_fail("验证码不能为空");
//}

//$r = VerifyCodeModel::getCodeByPhone($phone);
//if ($r == null || $r['status'] != 0 || strtotime($r['time']) + VERIFY_CODE_VALID_TIME*60 < time() || $verifyCode != $r['code']) {
//	operation_fail('验证码不正确或者已失效');
//}

if ($reward < 0 ) {
	operation_fail('任务奖励不能为负数');
}
// 转成分
$reward = intval(100*$reward);
//$r = TaskModel::publishTask($openId, $phone, $title, $desc, $reward, $now, $endTime, $address, $fromAddress, $lng, $lat);
$r = TaskModel::publishTask($publisher, $openId, $phone, $title, $desc, $reward, $now, $endTime, $address, '', $lng, $lat);
//VerifyCodeModel::deleteVerifyCode($phone);

if ($r) {
	json_put("result", $r);
} else {
	json_put("result", '发布任务失败');
}

json_output();
?>
