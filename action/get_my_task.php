<?php
require_once '../common/common.php';
$openId = $_SESSION['openid'];

$pageIndex = param("page", 1);

$phone = param("phone");

// 获取任务类型1:发布的任务，其它值是接受的任务
$type = param("type");

if ($phone == null) {
//	operation_fail("手机号不能为空");
}

if ($pageIndex <= 0) {
	$pageIndex = 1;
}

if ($type == 1) {
	$result = TaskModel::getMyPublishTask($openId, $pageIndex);
} else {
	$result = TaskModel::getMyAcceptTask($openId, $pageIndex);
}

json_put("result", $result);
json_output();

?>
