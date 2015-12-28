<?php
require_once 'permisson.php';
require_once '../common/common.php';
$name = param("nickname");
$pageIndex = param("page", 1);
if ($name == null) {
	$status = param('status', AccountExtra::STATUS_APPLYING);
	$r = AccountExtra::getAppliers($status);
} else {
	$r = AccountExtra::getAppliersByName($name, $pageIndex);	
}
json_put("result", $r);
json_output();

?>
