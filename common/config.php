<?php
// 这个脚本会在最开始的时候被调入，因此这个脚本的内容不应该依赖其他文件（除非直接require_once）
define('GC_COLORFUL_CHAT', FALSE);
if (!defined('CHECK_ADMIN_PERMISSION')) define('CHECK_ADMIN_PERMISSION', 0);

if (!defined('CHECK_SESSION')) define('CHECK_SESSION', 1);
error_reporting(E_ALL & ~ E_NOTICE);

// 服务器脚本的一些配置
if (!defined('CHECK_IMEI')) define('CHECK_IMEI', 0); // 是否开启IMEI检查
if (!defined('ALLOW_WEB_ACCESS')) define('ALLOW_WEB_ACCESS', 1); // 是否是WEB访问
if (!defined('CHECK_USER_AGENT')) define('CHECK_USER_AGENT', 1); // 是否检查UserAgent
if (!defined('FORBID_VIRTUAL_MACHINE')) define('FORBID_VIRTUAL_MACHINE', 0); // 是否禁止虚拟机
if (!defined('GIANT_LOCK')) define('GIANT_LOCK', 0);  // 是否开启全局锁
if (!defined('FIREWALL_ENABLE')) define('FIREWALL_ENABLE', 0); // 是否开启防火墙
if (!defined('SQL_PROFILE_ENABLE')) define('SQL_PROFILE_ENABLE', 0); // 是否开启SQL Profile
if (!defined('SCRIPT_PROFILE_ENABLE')) define('SCRIPT_PROFILE_ENABLE', 1); // 是否开启脚本SCRIPT的Profile
if (!defined('CHECK_TOKEN')) define('CHECK_TOKEN', 0);  // 是否开启TOKEN检查
if (!defined('PAGE_TABLE_SIZE')) define('PAGE_TABLE_SIZE', 20);
if (!defined('VERIFY_CODE_VALID_TIME')) define('VERIFY_CODE_VALID_TIME', 15); //验证码有效时间15分钟

if (!defined('APPID')) define('APPID', 'wx85235fd97431e3b6');
if (!defined('APPSECRET')) define('APPSECRET', '7a203127afcad07db25843a91fe81a2d');
if (!defined('DOMAIN')) define('DOMAIN', 'www.yigesh.cn');

$ADMIN_OPENID = array (
	"o-Sn7w2_fn2S74S_eA6Ft8rLF4bQ",
	"o-Sn7w4hWiqysbCjj_fDTdmvV-6U",
	"o-Sn7w9M8a88Fnz8NJEzu_HuIf0s",
);

?>
