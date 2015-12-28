<?php
require_once 'permisson.php';
require_once '../common/common.php';

$openid = param("openid", null);

$r = AccountExtra::getInfo($openid);
$account = Account::getAccount($openid);
if($r == null) {
	$r = array();
}

if ($account['phone'] != null) {
	$r['contact_phone'] = $account['phone'];
}
$r["nickname"] = $account["nickname"];

$publishTaskCount = TaskModel::getPublishTaskCountById($openid);
$acceptTaskCount = TaskModel::getAcceptTaskCountById($openid);

$r["publishTaskCount"] = $publishTaskCount;

$r["acceptTaskCount"] = $acceptTaskCount;
 
json_put("result", $r);
json_output();

?>
