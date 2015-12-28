<?php
require_once '../common/common.php';
$openId = $_SESSION['openid'];

$taskId = param('taskId');
//$acceptPhone = param("acceptPhone");
$verifyCode = param("verifyCode");

if ($taskId == null) {
	operation_fail('任务ID不能为空');
}


$account = Account::getAccount($openId);
if ($account["user_scheme"] != 1) {
	operation_fail("请先去认证快递资格,然后才能接单");
}

$accountExtra = AccountExtra::getInfo($openId);
$acceptPhone = $accountExtra["contact_phone"];

$task = TaskModel::getTaskById($taskId);
if ($task == null) {
	operation_fail("任务不存在");
}

if ($task['status'] != TaskModel::STATUS_PUBLISHING) {
	operation_fail("任务不是发布中，不能接单");
}

if ($task['publisher_openid'] == $openId) {
	operation_fail("不能接自己发布的单");
}
//if ($verifyCode == null) {
//	operation_fail("验证码不能为空");
//}

/*$r = VerifyCodeModel::getCodeByPhone($acceptPhone);
if ($r == null || 
	$r['status'] != 0 || 
	strtotime($r['time']) + VERIFY_CODE_VALID_TIME*60 < time() ||
	$verifyCode != $r['code']) {
	operation_fail('验证码不正确或者已失效');
}

VerifyCodeModel::deleteVerifyCode($acceptPhone);
*/

$r = TaskModel::acceptTask($taskId, $openId, $acceptPhone);
if ($r) {
	json_put("result", "接单成功");
} else {
	json_put("result", '发布任务失败');
}

json_output();
?>

