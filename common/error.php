<?php

// 游戏相关错误码 
define('ERR_PLAYER_NOT_EXISTS', 1001);
define('ERR_INVALID_REQUEST', 1002); // invalid request means player need to update its hero information
define('ERR_PLAYER_BANNED', 1003);

define('ERR_INVALID_NAME_OR_PASS', 1003);
define('ERR_GAME_OPERATION_FAIL', 1004); // 操作失败。这种失败是可能的，且不是致命的。
define('ERR_UPGRADE_ITEM_FAIL', 2001); 
define('ERR_SERVER_DOWN', 3000);
define('ERR_SERVER_MOVED', 3001);

/**
 * 错误、异常定义
 */



// 数据库错误
class DBError extends Exception {
	public function __construct($message = "") {
		$mysq_error = mysql_error();
		$code = mysql_errno();
		if ($message) {
			$mysq_error = $mysq_error . " (" . $message . ")"; 
		}
		parent::__construct($mysq_error, $code);
	}
}

// 数据库查询错误
class DBQueryError extends DBError {
	
	public $sql;
	
	public function __construct($sql) {
		$this->sql = $sql;
		parent::__construct($sql);
	}
	
}

// 登录相关错误
class NotLoginError extends Exception {
	public function __construct() {
		parent::__construct("尚未登录");
	}
}

class InvalidParamError extends Exception {
	public function __construct($message="非法请求参数") {
		parent::__construct($message, ERR_INVALID_REQUEST);
	}
}

class GameOperationFail extends Exception {
	public function __construct($message="操作失败") {
		parent::__construct($message, ERR_GAME_OPERATION_FAIL);
	}
}

class PlayeredBannedError extends Exception {
    public function __construct() {
        parent::__construct("该账号已经被禁用", ERR_PLAYER_BANNED);
    }
}

function error_exit($message, $error=ERR_INVALID_REQUEST) {
	print urldecode(json_encode(array(
		"error" => arrayRecursive($error, 'urlencode'), 
		"message" => arrayRecursive($message, 'urlencode'), 
	))); 
	exit(); 
}

function operation_fail($message, $error=ERR_GAME_OPERATION_FAIL) {
	print urldecode(json_encode(array(
		"error" => arrayRecursive($error, 'urlencode'), 
		"message" => arrayRecursive($message, 'urlencode'), 
	))); 
	exit(); 
}

?>
