<?php
require_once 'permisson.php';
require_once '../common/common.php';

$openid = param("openid", null);

$publishTasks = TaskModel::getMyPublishTask($openid);
$acceptTasks = TaskModel::getMyAcceptTask($openid);
$tasks = array_merge($publishTasks, $acceptTasks);
json_put("result", $tasks);
json_output();
?>
