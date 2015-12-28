<?php
require_once 'firewall.php'; 
require_once 'config.php';

$WEIXIN_URLS = array (
	"WEIXIN_LOGIN" => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . APPID . '&redirect_uri=http%3A%2F%2F'.DOMAIN.'%2Faction%2Flogin_callback.php&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect',
	"WEIXIN_TR_LOGIN"=>'https://open.weixin.qq.com/connect/qrconnect?appid=' . APPID .'&redirect_uri=http%3A%2F%2F'.DOMAIN.'%2Faction%2Flogin_callback.php&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect',
);

$_giant_lock_fp = FALSE; 
function giant_lock() {
    global $_giant_lock_fp; 
    $_pre_lock_time = microtime(TRUE); 
    $pid = param("pid", null);
	if ($pid != null)
		$_giant_lock_fp = fopen("/tmp/pid_".$pid.".lock", "w+"); 
	else 
		$_giant_lock_fp = fopen("/tmp/task_gaint.lock", "w+"); 
    flock($_giant_lock_fp, LOCK_EX);
    $_lock_time = microtime(TRUE) - $_pre_lock_time; 
    // 保存锁定时间
    profile_record_lock_time($_lock_time);
}

function giant_unlock() {
    global $_giant_lock_fp; 
    if ($_giant_lock_fp !== FALSE) {
        fclose($_giant_lock_fp);
		$_giant_lock_fp = FALSE;
    }
}

function checkAdminPermisson() {
	$openid = $_SESSION['openid'];
	global $ADMIN_OPENID;
	if (in_array($openid, $ADMIN_OPENID)) {
		return true;
	} else {
		return FALSE;	
	}
}

function checksession()
{
	//使用会话内存储的变量值之前必须先开启会话
	session_start();
	//使用一个会话变量检查登录状态
	if(isset($_SESSION['openid'])){
    	//echo 'You are Logged as '.$_SESSION['name'].'<br/>';
    	//点击“Log Out”,则转到logOut页面进行注销
    	//echo '<a href="teacher_logout.php"> Log Out('.$_SESSION['name'].')</a>';
	} else {
		global $WEIXIN_URLS;
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MicroMessenger")) {
			$home_url = $WEIXIN_URLS["WEIXIN_LOGIN"];
		} else {
			$home_url = $WEIXIN_URLS["WEIXIN_TR_LOGIN"];
		}
		header('Location:'.$home_url);
	}
}		
function redirect($url) {
	header("Location: " . $url, TRUE, 302); 
}

function p($text) {
	echo htmlspecialchars($text, null, "utf-8");
}

function ifelse($expr, $iftrue, $iffalse) {
	if ($expr) return $iftrue;
	else return $iffalse;
}

function E($text) {
    return htmlspecialchars($text, NULL, "utf-8");
}
function nonull($x, $ifnull) {
	return ($x === NULL) ? $ifnull : $x;
}

function isdev() {
    // 如果服务端关闭了dev选项，则肯定不是dev
    if (apcfetch("nodev") !== FALSE) return FALSE; 
    // 如果客户端指定了不是dev，则肯定不是dev
    $undev = intval(param("undev", 0)); 
    if ($undev) return FALSE;
	global $dev_imeis;
    $_imei = imei(); 
	return isset($dev_imeis[$_imei]);
}

$DEBUG_FIGHT = True;
function debug_fight($str) {
	global $DEBUG_FIGHT;
	if ($DEBUG_FIGHT) echo "::: " . htmlspecialchars($str) . "<br />";
}

function roundint($value) {
	return intval(round($value));
}

function check_exists($arg_name) {
	if (!isset($_REQUEST[$arg_name])) {
		throw new InvalidParamError("请求不完整");
	}
	return $_REQUEST[$arg_name];
}

function param($arg_name, $default=null) {
	if (!isset($_REQUEST[$arg_name])) {
		return $default;
	} else 
		return $_REQUEST[$arg_name];
}

function str_right($string, $n) {
	return substr($string, strlen($string)-$n,$n); 
}


function imei() {
    $im =  param("imei");
    if ($im == null) return null;
    if (substr($im, strlen($im)-1) === "?") {
        $im = substr($im, 0, strlen($im)-1);
    }
    return $im;
}

function model() {
	return param("M");
}

function isiOS() {
	$m = param("m");
	if ($m == null) {
		$m = param("M");
	}
	if ($m == null || (strpos($m, "Simulator") === false && strpos($m, "iPad") === false && strpos($m, "iPhone") === false && strpos($m, "iPod") === false)){
		return false;
	}
	return true;
}

function pid($not_null=true) {
	$pid = param("pid", null);
	if (!isset($pid)) $pid = param("sina_id");
	if (!isset($pid) && $not_null) throw new InvalidParamError("请求中没有玩家ID");
	return $pid;
}


function ver() {
	return param("v", ""); 
}

class PageAccessTime {
    public $count; 
    public $total_time;
    public function __construct($script) {
        $this->script = $script;
        $this->count = 0; 
        $this->total_time = 0; 
    }  
    
    public function average() {
        if ($this->count == 0) return 0; 
        else return $this->total_time / $this->count; 
    }
}

function gzip($content) // $content 就是要压缩的页面内容，或者说饼干原料
{
    global $__script_start_time;
    if ($__script_start_time) {
        $t = microtime(true) - $__script_start_time; 
        if (SCRIPT_PROFILE_ENABLE) {
            $times = new ApcDict("PhpExecuteTimes"); 
            $script = $_SERVER["SCRIPT_NAME"]; 
            $pa = $times->fetch($script); 
            if ($pa === FALSE) {
                $pa = new PageAccessTime($script);
                $pa_exists = FALSE;
            } else {
                $pa_exists = TRUE;
            }
            $pa->count += 1; 
            $pa->total_time += $t;
            $times->store($script, $pa, $pa_exists); 
        }
            
        if (FIREWALL_ENABLE) {
            firewall_finish($t);
        }
    }
    
	// 先关闭所有的mysql连接
	global $dblink; 
	if ($dblink) mysql_close();

    $content = gzencode($content, 9); // 为准备压缩的内容贴上“//此页已压缩”的注释标签，然后用zlib提供的gzencode()函数执行级别为9的压缩，这个参数值范围是0-9，0表示无压缩，9表示最大压缩，当然压缩程度越高越费CPU。
    //然后用header()函数给浏览器发送一些头部信息，告诉浏览器这个页面已经用GZIP压缩过了！
    header("Content-Encoding: gzip"); 
    header("Vary: Accept-Encoding");
    header("Content-Length: ".strlen($content));
    return $content; //返回压缩的内容，或者说把压缩好的饼干送回工作台。
}

function format_time($t) {
	$s = "";
	$DAY = 24 * 3600;
	$HOUR = 3600;
	$MIN = 60;
	
	if ($t >= $DAY) {
		$s .= intval($t / $DAY) . "天";
		$t = $t % $DAY;
	}
	
	if ($t >= $HOUR || $s != "") {
		$s .= intval($t / $HOUR) . "小时";
		$t = $t % $HOUR;
	}
	
	if ($t >= $MIN || $s != "") {
		$s .= intval($t / $MIN) . "分";
		$t = $t % $MIN;
	}
	
	$s .= $t . "秒";
	return $s; 
}

function format_dateinterval(DateInterval $di, $mask="ymdhis") {
    $f = "";
    if ($di->y != 0 && strstr($mask, "y") !== FALSE) $f .= "%y年";
    if ($di->m != 0 && strstr($mask, "m") !== FALSE) $f .= "%m月";
    if ($di->d != 0 && strstr($mask, "d") !== FALSE) $f .= "%d天";
    if ($di->h != 0 && strstr($mask, "h") !== FALSE) $f .= "%h小时";
    if ($di->i != 0 && strstr($mask, "i") !== FALSE) $f .= "%i分钟";
    if ($di->s != 0 && strstr($mask, "s") !== FALSE) $f .= "%s秒";
    return $di->format($f);
}

function bjtime() {
	return time() + 8*3600;
}

function daytime($t=null) {
	if (!isset($t)) $t = time();
	$t += 8 * 3600;
	$t = intval($t / 86400) * 86400;
	$t -= 8 * 3600;
	return $t; 
}

function sameday($t1, $t2) {
	return intval(($t1 + 8*3600) / (24*3600)) == intval(($t2 + 8*3600) / (24*3600));
}

function gethour($t) {
	return (intval($t / 3600)) % 24;
}

function startsWith($haystack,$needle,$case=true) {
    if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
    return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
}

function endsWith($haystack,$needle,$case=true) {
    if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
    return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
}

require_once 'error.php';

// 检查PID是否合法：没有被BAN并且可以从该imei访问
function checkpid($pid) {
	$imei = imei();
	$pid_e = sqlstr($pid);
	$login_key = "LOGIN-$pid";
	$login_imeis = apcfetch($login_key);
	if ($login_imeis === FALSE) {
		$login_imeis = array(); 
		$data = db_fetch_all("SELECT imei FROM login WHERE pid=$pid_e");
		foreach ($data as $e) {
			$imei = $e["imei"]; 
			$login_imeis[$imei] = 1; 
		}
		apcstore($login_key, $login_imeis); 
	        $login_imeis = apcfetch($login_key);
		//operation_fail($login_imeis);
		on_apc_miss(); 
	} else {
	    on_apc_hit();
	}
	
	if (!isset($login_imeis[$imei])) {
		operation_fail("你的登陆信息已失效，为了保护账号，请退出重新登陆");
	}
}

function admin_verify() 
{
    $email = isset($_COOKIE["email"]) ? $_COOKIE["email"] : null;
    $password = isset($_COOKIE["password"]) ? $_COOKIE["password"] : null;
    $verified = ($email == "hello@gmail.com") && ($password == "hello");
    if (!$verified) {
        echo "<a href=\"/admin.php\">访问未认证！请返回ADMIN页面进行认证！</a><br/>";
        exit();
    }
}

function admin_return() 
{
    $email = isset($_COOKIE["email"]) ? $_COOKIE["email"] : null;
    $password = isset($_COOKIE["password"]) ? $_COOKIE["password"] : null;
	
	redirect("admin.php?email=$email&password=$password");
}

function on_apc_hit()
{
    $rec = apcfetch("APC_HIT_RATE");  
    if ($rec == FALSE) $rec = 1.0;
    $rec = $rec * 0.99 + 0.01;
    apcstore("APC_HIT_RATE", $rec); 
}

function on_apc_miss() 
{
    $rec = apcfetch("APC_HIT_RATE");  
    if ($rec == FALSE) $rec = 0.0;
    $rec = $rec * 0.99;
    apcstore("APC_HIT_RATE", $rec); 
}

function apc_hit_rate() 
{
    $rec = apcfetch("APC_HIT_RATE");
    if ($rec == FALSE) return 0; 
    else return $rec; 
}

function choose_db($dbindex) {
	global $whichdb;
	$whichdb = $dbindex;
	print $whichdb;
}

function array_equal($array1, $array2) {
    if (count($array1) != count($array2)) return FALSE;
    foreach ($array1 as $k1 => $v1) {
        if (!array_key_exists($k1, $array2)) return FALSE;
        $v2 = $array2[$k1];
        if (is_array($v1) && is_array($v2)) {
            if (!array_equal($v1, $v2)) return FALSE;
        } else if (!is_array($v1) && !is_array($v2)) {
            if ($v1 != $v2) return FALSE;
        } 
        else return FALSE;
    }
    return TRUE;
}

function curl_read_json($url) {
    $server_ip = $_SERVER["SERVER_ADDR"]; // 本地地址
    if (strstr($url, $server_ip) !== FALSE) {
        // 在访问当前服务器！ FIXME: 这个判断还有待完善
        $timeout = 5000;
    } else {
        $timeout = 5000;
    }
    $data = curl_read($url, $timeout);
    $data = alt_gzdecode($data);
    $json = json_decode($data, TRUE);
    if ($json["error"] != 0) throw new GameOperationFail($json["message"]);
    return $json;
} 

function curl_read($url, $timeout_ms = 5000) {
    giant_unlock(); // curl is slow, so unlock giant lock before cUrl
    try {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); // cross
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout_ms);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        $data = curl_exec($ch); 
        if(!curl_errno($ch)){
            curl_close($ch); 
        } else {
            $error = curl_error($ch);
            curl_close($ch); 
            throw new Exception($error); 
        } 
        giant_lock();
        return $data; 
    } catch (Exception $ex) {
        giant_lock();
        throw $ex; 
    }

} 

function alt_gzdecode($str) { 
  // seed with microseconds since last "whole" second 
  mt_srand((float)microtime()*1000000); 
  $eh="/tmp/php-" . md5(mt_rand(0,mt_getrandmax())) . ".gz"; 

  $fd=fopen($eh,"w"); 
  fwrite($fd,$str); 
  fclose($fd); 

  unset($str); 

  $fd = gzopen ($eh, "r"); 
  while (1==1) { 
    $s=gzread($fd,10240); 
    if ("$s" == "") { 
      break; 
    } 
    $str=$str . $s; 
  } 
  unlink($eh); 

  return $str; 
}


function checkToken() {
	if (!CHECK_TOKEN) return;
	if (strstr($_SERVER["REQUEST_URI"], "player_fight.php"))
		return;
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]; 

	$token = param("token", null);
	if ($token == null) operation_fail("无效的请求，请升级到最新版本");
	$suburl = substr($url, 0, strlen($url) - strlen($token) - 7);
	if (md5("63485c0d9fa8583f3bc3bbbfe5d2295e".$suburl) != $token)
		error_exit("无效的请求，请升级到最新版本");
}

function getIp()
{
	$ip="";
	if($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
		$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
	}
	elseif($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
		$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
	}
	elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]){
		$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
	}
	elseif (getenv("HTTP_X_FORWARDED_FOR")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}
	elseif (getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	}
	elseif (getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	}	
	else{
		$ip = "Unknown";
	}
	return $ip;
}

function uploadFile ($fileName, $type) {
	$openId = $_SESSION['openid'];
	$filePath = $type . "_" . $openId;
	$imageType = array(
		"image/gif"=>"gif",
		"image/jpeg"=>"jpg",
		"image/pjpeg"=>"jpg"
	);
        //operation_fail($_FILES[$fileName]["type"]);
	if ((($_FILES[$fileName]["type"] == "image/gif")
		|| ($_FILES[$fileName]["type"] == "image/jpeg")
		|| ($_FILES[$fileName]["type"] == "image/pjpeg"))
		&& ($_FILES[$fileName]["size"] < 5000000)) {
		$filePath .= "." . $imageType[$_FILES[$fileName]["type"]];
		if ($_FILES[$fileName]["error"] > 0)
		{
			operation_fail($_FILES[$fileName]["error"]);
		} 
		else 
		{
			//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			//echo "Type: " . $_FILES["file"]["type"] . "<br />";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			$moveResult = move_uploaded_file($_FILES[$fileName]["tmp_name"], "../upload/".$filePath);
			//echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
			if (!$moveResult) {
				operation_fail("save file failed.".$_FILES[$fileName]["tmp_name"]."new path:"."../upload/".$filePath);
			}
			return "upload/".$filePath;

		}
	}
	else
	{
		operation_fail("无效的图片, 图片必须是gif、jpeg、pjpeg、大小小于5MB");
	}
}

function check_password($pass) {
//	if ($name != trim($name)) return "英雄名字不能包含空格";
	$digit = false;
	$lower = false;
	$upper = false;
	if ($pass == null || $pass == "") return "密码不能为空"; 
	$len = strlen($pass);
	for ($i = 0; $i < $len; $i++) {
	    $c = substr($pass, $i, 1);
	    if (ctype_cntrl($c)) return "密码不能包含控制符";
	    if (ctype_space($c) || $c == '　') return "密码不能包含空格";
		if (ctype_digit($c)) $digit = true;
		if (ctype_lower($c)) $lower = true;
		if (ctype_upper($c)) $upper = true;
	}
	if (!$digit || !$upper || !$lower || $len < 8) {
	//	return "密码必须至少8位，且至少包含1个大写字母，1个小写字母，1个数字";
	}
	
	return null; 
}

require_once 'json_util.php';

?>