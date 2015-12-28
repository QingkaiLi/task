<?php
require_once '../common/common.php';
$openId = $_SESSION['openid'];

$taskId = param('taskId');

if ($taskId == null) {
	operation_fail('任务ID不能为空');
}

$publisher = param('user');

$phone = param('phone');
//$title = param('title');
$desc = param('desc');

$endTime = param('endTime');
$reward = param('tip');
$address = param('address');

//$fromAddress = param('fromAddress');

$lng = param('lng');//经度
$lat = param('lat');//续度
$reward = intval(100*$reward);
$r = TaskModel::updateTask($taskId, $publisher, $openId, $phone, $desc, $reward, $endTime, $address, $lng, $lat);

if ($r) {
	json_put("result", $r);
} else {
	json_put("result", '更新任务失败');
}
json_output();

?>

