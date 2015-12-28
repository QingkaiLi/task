<?php

/**
 * 这里都是数据库相关的一些功能函数
 */

require_once 'error.php';
require_once 'profile.php';

// 玩家表的表信息
$GLOBALS["PLAYER_TABLE"] = array(
	"name" => "player",
	"primary" => "uid",
	"stringfields" => array("uid"), 
);

// 返回某个值在SQL语句中的对应字符串表达
function db_repr($value) {
	if (is_string($value)) $value = "'" . $value . "'";
	else return "" . $value;
}

// 执行SQL语句
function db_execute($sql) {
    $_lock_fp = fopen("/tmp/task_db_lock", "w+"); 
    flock($_lock_fp, LOCK_EX);
    try {
        require_once 'connectdb.php';
        if (SQL_PROFILE_ENABLE) profile_sql_start($sql);
        $r = mysql_query($sql);
        if (SQL_PROFILE_ENABLE) profile_sql_end(); 
        if (!$r) {
        	operation_fail($sql);
            throw new DBQueryError($sql);
        }
        fclose($_lock_fp);
        return $r;
    } catch (Exception $ex) {
        fclose($_lock_fp);
        throw $ex; 
    }
}

// 执行SQL语句，并返回第一个结果
function db_fetch_one($sql) {
	$result = db_execute($sql);
	$assoc = mysql_fetch_assoc($result);
	if ($result == null || $assoc == false) return null; 
	else return $assoc;
}

// 执行SQL语句，并返回所有结果
function db_fetch_all($sql) {
	$result = db_execute($sql);
	$all = array();
	while ($elm = mysql_fetch_assoc($result)) {
		array_push($all, $elm);
	}
	return $all;
}

function db_fetch_value($sql, $defaultValue=null) {
	$one = db_fetch_one($sql);
	if ($one == null) return $defaultValue;
	foreach ($one as $k => $v) {
		return $v;
	}
	return $defaultValue;
}

function sql_escape($val, $is_str) {
	if ($is_str) {
		return "'" . mysql_escape_string($val) . "'";
	} else {
		return $val;
	}
}

// 更新数据库
function db_update($tableinfo, $old, $new, $primaryval=NULL) {
	$setstmt = "";
	$primary = $tableinfo["primary"];
	$tname = $tableinfo["name"];
	$stringfields = $tableinfo["stringfields"];
	
//	print_r($old);
//	print_r($new);
	
//	var_dump($old);
//	var_dump($new);
	foreach ($new as $k => $v) {
		assert(array_key_exists($k, $old));
		if ($k == $primary) {
			// 主键不参与set语句
			assert($old[$primary] == $new[$primary]); // 主键必须保持不变
			$primaryval =  $new[$primary];
			continue;
		} 
		
		if ($v != $old[$k]) {
			if ($setstmt != "") $setstmt = $setstmt . ", ";
			$v = sql_escape($v, array_search($k, $stringfields) !== false);
			$setstmt = $setstmt . "$k=$v";
		}
	}
	assert($primaryval != NULL);
	$primaryval = $v = sql_escape($primaryval, array_search($primary, $stringfields) !== false);
	
	if ($setstmt != "") {
		// 需要执行update语句
		$sql = "UPDATE $tname SET $setstmt WHERE $primary=$primaryval";
//		print_r($sql);
		db_execute($sql);
	}
}

/**
 * 绑定参数列表
 */
function bindParams(&$sql, $array) {
	$times = 0;
	foreach ($array as $key => $value) {
		bindParam($sql, $key + 1, $value, $times);
	}
}

/**  
 * 模拟简单的绑定参数过程  
 *  
 * @param string $sql    SQL语句  
 * @param int $location  问号位置  
 * @param mixed $var     替换的变量  
 * @param string $type   替换的类型  
 */ 
//这里要注意，因为要"真正的"改变$sql的值，所以用引用传值
function bindParam(&$sql, $location, $var, &$times=0, $type='STRING') {
	if ($var != null) {  
	    //确定类型  
	    switch ($type) {  
	        //字符串  
	        default:                    //默认使用字符串类型  
	        case 'STRING' :  
	            $var = addslashes($var);  //转义  
	            $var = "'".$var."'";      //加上单引号.SQL语句中字符串插入必须加单引号  
	            break;  
	        case 'INTEGER' :  
	        case 'INT' :  
	            $var = (int)$var;         //强制转换成int  
	            break;
			default:
				break;
	        //还可以增加更多类型..  
	    } 
	} else {
		$var = 'null';
	} 
    //寻找问号的位置  
    for ($i=1, $pos = 0; $i<= $location - $times; $i++) {  
        $pos = strpos($sql, '?', $pos+1);  
    }  
    //替换问号  
    $sql = substr($sql, 0, $pos) . $var . substr($sql, $pos + 1); 
	$times++;
}  

// 防注入
function sqlstr($str) {
	if ($str === null) {
		return "null";
	} else {
		return "'" .addslashes(mysql_escape_string($str)) . "'"; 
	}
}

?>
