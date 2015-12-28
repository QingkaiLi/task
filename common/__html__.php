<?php
// 这个文件可以作为HTML页面（例如admin.php）里第一个include的文件，用于定义一些预先设定的参数
define('ALLOW_WEB_ACCESS', 1);  // 这是WEB访问
define('CHECK_USER_AGENT', 0);  // 不要检查客户端UserAgent
define('CHECK_IMEI', 0);        // 不要检查IMEI 
define('FORBID_VIRTUAL_MACHINE', 0);    // 允许虚拟机
?>
