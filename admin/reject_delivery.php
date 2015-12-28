<?php
require_once 'permisson.php';
require_once '../common/common.php';

$openId = $_SESSION['openid'];
$apply_openid = param("applier");

$reason = param("reason");
$r = AccountExtra::reject($apply_openid, $reason);

if ($r) {
	json_put("result", "已拒绝");
} else {
	json_put("result", "操作失败");
}
json_output();
?>

