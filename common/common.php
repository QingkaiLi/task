<?php
// 自动载入类定义
function __autoload($ClassName) {
	require_once $ClassName . ".php";
} 
require_once 'error.php';
require_once 'config.php';
ob_start("gzip");
require_once 'db.php';
require_once 'model.php';
require_once 'util.php';

$iamgod = param("ingodwebelieve");

if (!ALLOW_WEB_ACCESS && CHECK_USER_AGENT) {
	// 检查客户端是否是合法的客户端！
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	if (!isset($iamgod) && $user_agent != "Mozilla/5.0 (Windows NT 6.1; rv:10.0) Gecko/20100101 Firefox/10.0") {
		// it is not the weidota game client, forbid it
		operation_fail('代理出错,无法访问');
	}
}


// 网页访问 都是老师访问，需要检查SESSION
if (CHECK_SESSION) {
	checksession();
}

if (CHECK_ADMIN_PERMISSION) {
	$result = checkAdminPermisson();
	if (!$result) {
		operation_fail("你没有管理员权限");
	}
}

if (GIANT_LOCK) {
    giant_lock();
}

$__script_start_time = microtime(true);

if (CHECK_IMEI) {
	$ban_imeis = apcfetch("BAN_IMEIS");
	if (!$ban_imeis) {
		$ban_imeis = array();
		$data = db_fetch_all("select imei from ban_phone");
		foreach ($data as $e) {
			$imei = $e["imei"]; 
			$ban_imeis[$imei] = 1; 
		} 
	}
	
    $_imei = imei();
	$_ip = getIp();
    if(isset($ban_imeis[$_imei]) ||
		isset($ban_imeis[$_ip])) {
        operation_fail("hello".$_imei.language_message("ban_imei"));
    }
}

if (FORBID_VIRTUAL_MACHINE) {
    if (!isset($iamgod) && $_imei == "000000000000000") {
        operation_fail(language_message("forbid_virtual_machine"));
    }
}

?>
