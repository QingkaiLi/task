<?php
require_once '../common/common.php';

$openId = $_SESSION['openid'];

$taskId = param('taskId');

if ($taskId == null) {
	operation_fail('任务ID不能为空');
}
$task = TaskModel::getTaskById($taskId);
if ($task == null) {
	operation_fail("任务不存在");
}

if ($task['publisher_openid'] != $openId) {
	operation_fail("只有任务发布者才能结束任务");
}
if ($task['status'] != TaskModel::STATUS_ACCEPTED) {
	operation_fail("当前任务状态不对，不能结束");
}

$r = TaskModel::finishTask($taskId, $openId);

if ($r) {
	json_put("result", "结束成功");
} else {
	json_put("result", '结束任务失败');
}
json_output();

?>

