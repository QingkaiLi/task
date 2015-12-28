<?php

require_once 'error.php';

$dblink = NULL; 
// weidota2012.mysql.rds.aliyuncs.com = 10.200.176.60
// rdsmyn7vffuifnm1366208919532.mysql.rds.aliyuncs.com = 10.242.178.23

function connect_db() {
	global $dblink; 
	$host = $_SERVER["HTTP_HOST"];
    list($server, $user, $pwd, $dbname) = array('localhost', 'root', 'dota1', 'app_task'); // debug only
    
	$dblink = mysql_connect($server, $user, $pwd);
	if (!$dblink) {
	//        $dblink = mysql_connect(strtolower($server), $user, $pwd);
	        if (!$dblink) {
	            throw new DBError();
	        }
	}
	mysql_select_db($dbname, $dblink);
}
connect_db();
?>
