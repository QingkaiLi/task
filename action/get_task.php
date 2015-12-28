<?php
require_once '../common/common.php';

$id = param("id");

$task = TaskModel::getTaskById($id);

json_put("task", $task);
json_output();

?>
