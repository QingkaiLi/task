<?php
require_once 'permisson.php';
require_once '../common/common.php';

$openId = $_SESSION['openid'];
$apply_openid = param("applier");

$r = AccountExtra::approve($apply_openid);

if ($r) {
	Account::approve($apply_openid);
	
	json_put("result", "审核通过");
} else {
	json_put("result", "审核失败");
}
json_output();
?>

