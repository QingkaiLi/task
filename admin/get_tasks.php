<?php
require_once 'permisson.php';
require_once '../common/common.php';
$pageIndex = param("page", 1);
$taskName = param("taskName", null);

// 跟据创建时间分页展示
$tasks = TaskModel::getAllTasks($taskName,$pageIndex);

json_put("result", $tasks);
json_output();
?>
