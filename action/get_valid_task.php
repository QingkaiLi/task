<?php
require_once '../common/common.php';

$lng = param('lng'); // 传入用户的经度
$lat = param('lat'); // 纬度

if ($lng == null || $lat == null) {
	operation_fail("经纬度错误");
}

$distance = param("distance", 50);
$page = param("page", 1);
$tasks = TaskModel::getValidTasks($lng, $lat, $distance, $page);

$tasksTotalCount = TaskModel::getValidTasksCount();

json_put("tasks", $tasks);
json_put("total", $tasksTotalCount);

json_output();

?>


