<?php

/**************************************************************
*
*    使用特定function对数组中所有元素做处理
*    @param  string  &$array     要处理的字符串
*    @param  string  $function   要执行的函数
*    @return boolean $apply_to_keys_also     是否也应用到key上
*    @access public
*
*************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
	if ($array == null) {
		return $array;
	}
	if (!is_array($array)) {
		$array = $function($array);
		return $array;
	}
	static $recursive_counter = 0;
	if (++$recursive_counter > 1000) {
		die('possible deep recursion attack');
	}
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			arrayRecursive($array[$key], $function, $apply_to_keys_also);
		} else {
			$array[$key] = $function($value);
		}

		if ($apply_to_keys_also && is_string($key)) {
			$new_key = $function($key);
			if ($new_key != $key) {
				$array[$new_key] = $array[$key];
				unset($array[$key]);
			}
		}
	}
	$recursive_counter--;
	return $array;
}


$_json_result = array(
    "error" => 0, 
);

function json_output() {
    global $_json_result; 
	arrayRecursive($_json_result, 'urlencode', false);
    print urldecode(json_encode($_json_result));
}

function json_toast($message) {
    global $_json_result; 
    if (!isset($_json_result["toast"])) {
        $_json_result["toast"] = array($message); 
    } else {
        array_push($_json_result["toast"], $message);
    }
}

function json_alert($message) {
    global $_json_result; 
    if (!isset($_json_result["alert"])) {
        $_json_result["alert"] = array($message); 
    } else {
        array_push($_json_result["alert"], $message);
    }
}

function json_put($key, $value) {
    global $_json_result;
    $_json_result[$key] = $value; 
}

function json_replace($new_json) {
    global $_json_result;
    $_json_result = $new_json; 
}

function json_fill($values) {
    global $_json_result;
    foreach ($values as $key => $value) {
        $_json_result[$key] = $value; 
    }
}

?>
