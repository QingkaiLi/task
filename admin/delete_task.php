<?php
require_once 'permisson.php';
require_once '../common/common.php';

$taskId = param('taskId');


$r = TaskModel::deleteTask($taskId);

if ($r) {
	json_put("result", "删除成功");
} else {
	json_put("result", '删除任务失败');
}
json_output();
?>


