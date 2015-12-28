<?php

require_once 'db.php';
require_once 'Reflecting.php';
require_once 'json_util.php';
require_once 'util.php';

class Model {
	
	public $data; 
	
	public function __construct($data) {
		$this->data = $data; 
	}
	
	public function hasField($name) {
		return array_key_exists($name, $this->data);
	}
	
	public function __get($name) {
		if (isset($this->data[$name])) return $this->data[$name];
		else return null; 
	}
	
	public function __put($name, $value) {
		$this->data[$name] = $value;
	} 
}

class Account extends Model {
	const USER_DELIVERY = 1; // 1代表已认证为快递员
	public static function getAccount($openid) {
		$sql = "SELECT *FROM Account WHERE openid = ?";
		bindParams($sql, array($openid));
		$r = db_fetch_one($sql);
		return $r;
	}
	
	public static function createAccount($openid, $nickName) {
		$sql = "INSERT INTO Account(openid, nickname) values(?, ?)";
		bindParams($sql, array($openid, $nickName));

		$r = db_execute($sql);
		return $r;
	}

	public static function updatePhone($phone, $openid) {
		$sql = "UPDATE Account SET phone=? where openid = ?";
		bindParams($sql, array($phone, $openid));
		$r = db_execute($sql);
		return $r;
	}
	
	public static function approve($openid) {
                $sql = "UPDATE Account SET user_scheme = 1 WHERE openid = ?";
                bindParams($sql, array($openid));

                $r = db_execute($sql);
                return $r;
        }

}

class AccountExtra extends Model {
	const STATUS_REJECT = -1;
	const STATUS_APPLYING = 0;
	const STATUS_ACCEPT = 1;
	// 申请快递资格
	public static function applyDelivery($openid, $icon, $address, $fullname,
	 $idcard, $contact, $contact_phone, $card_pic, $inviter) {
		$sql = "INSERT INTO AccountExtra(openid, icon, address, fullname, idcard, contact, contact_phone,
		 card_pic, inviter, modified_time) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		bindParams($sql, array($openid, $icon, $address, $fullname, $idcard, 
		$contact, $contact_phone, $card_pic, $inviter, date('Y-m-d H:i:s', time())));
		$r = db_execute($sql);
		return $r;
	}
	public static function updateDelivery($openid, $icon, $address, $fullname,
	 	$idcard, $contact, $contact_phone, $card_pic, $inviter) {
		$sql = "UPDATE AccountExtra SET status = 0, icon = ?, address = ?, fullname = ?, idcard = ?, contact = ?,
		 contact_phone = ?, card_pic = ?, inviter =?, modified_time = ? WHERE openid = ? AND status = -1";
		bindParams($sql, array($icon, $address, $fullname, $idcard, $contact, $contact_phone, 
			$card_pic, $inviter, date('Y-m-d H:i:s', time()), $openid));
		$r = db_execute($sql);
		return $r;
	}
	public static function getInfo($openid) {
		$sql = "SELECT *FROM AccountExtra WHERE openid=?";
		bindParams($sql, array($openid));
		$r = db_fetch_one($sql);
		return $r;
	}
	
	public static function approve($openid) {
		$sql = "UPDATE AccountExtra SET status = 1 WHERE openid=? AND status = 0";
		bindParams($sql, array($openid));
		$r = db_execute($sql);
		return $r;
	}
	
	public static function reject ($openid, $reason) {
		$sql = "UPDATE AccountExtra SET status = -1, info=? WHERE openid=? AND status = 0";
		bindParams($sql, array($reason, $openid));
		$r = db_execute($sql);
		return $r;
	}
	
	public static function getAppliers($status, $pageIndex = 1) {
		$pageSize = PAGE_TABLE_SIZE;
		$offset = ($pageIndex - 1) * $pageSize;
		$sql = "SELECT * FROM AccountExtra WHERE status = $status order by modified_time limit $pageSize offset $offset";
	//	bindParams($sql, array($status, $pageSize, $offset));
		$r = db_fetch_all($sql);
		return $r;
	}
	
	public static function getAppliersByName($name, $pageIndex = 1) {
		$pageSize = PAGE_TABLE_SIZE;
		$offset = ($pageIndex - 1) * $pageSize;
		$nickname=sqlstr('%'.$name.'%');
		$sql = "SELECT * FROM Account WHERE nickname like $nickname limit $pageSize offset $offset";
		$r = db_fetch_all($sql);
		return $r;
	}
}

class TaskModel extends Model {
	const STATUS_CLOSED = 0;
	const STATUS_PUBLISHING = 1;
	const STATUS_ACCEPTED = 2;
	const STATUS_FINISHED = 3;
	
	public static function publishTask($publisher, $publisherOpenid, $publisherPhone, $title, $desc, $reward, $startTime, $endTime, $address, $fromAddress, $lng, $lat) {
		$sql = 'INSERT INTO Task(publisher_name,publisher_openid, publisher_phone, title, description, reward, start_time, end_time, address, from_address, lng, lat) 
							values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		bindParams($sql, array($publisher, $publisherOpenid, $publisherPhone, $title, $desc, $reward, $startTime, $endTime, $address, $fromAddress, $lng, $lat));
		$r = db_execute($sql);
		return $r;
	}	
	public static function acceptTask($taskId, $acceptOpenid, $acceptPhone) {
		$sql = "UPDATE Task SET accepter_openid = ? , accepter_phone = ?, accept_time=?, status=2 WHERE id=? AND status=1 AND end_time > now()";
		bindParams($sql, array($acceptOpenid, $acceptPhone, date('Y-m-d H:i:s', time()), $taskId));	
		$r = db_execute($sql);
		return $r;
	}
	
	public static function updateTask($taskId, $publisher, $publishOpenid, $publisherPhone, $desc, $reward, $endTime, $address, $lng, $lat) {
		$sql = "UPDATE Task SET publisher_name=?, publisher_phone=?, description=?, reward=?, end_time=?, address=?, lng=?, lat=?, status=1
			WHERE id=? AND status<=1 AND publisher_openid=?";
		bindParams($sql, array($publisher, $publisherPhone, $desc, $reward, $endTime, $address, $lng, $lat, $taskId, $publishOpenid));	
			
		$r = db_execute($sql);
		return $r;
	}
	
	public static function closeTask($taskId, $publishOpenid)
	{
		$sql = "UPDATE Task SET status = 0 WHERE id=? AND publisher_openid=? AND status=1";
		bindParams($sql, array($taskId, $publishOpenid));	
			
		$r = db_execute($sql);
		return $r;	
	}
	 public static function finishTask($taskId, $publishOpenid)
    {
		$sql = "UPDATE Task SET status = 3 WHERE id=? AND publisher_openid=? AND status=2";
		bindParams($sql, array($taskId, $publishOpenid));

		$r = db_execute($sql);
		return $r;
	}

	
	public static function deleteTask($taskId) {
		$sql = "DELETE FROM Task WHERE id = ?";
		bindParams($sql, array($taskId));
		$r = db_execute($sql);
	}
	
	// 查询已发布并且有效的、当前可以接的任务
	public static function getValidTasksCount() {
		$sql = "SELECT count(1) FROM Task WHERE status=1 AND start_time < now() and end_time > now()";
		$count = db_fetch_value($sql);
		return $count;
	}
	
	public static function getAllTasks($taskName, $pageIndex) {
		$pageSize = PAGE_TABLE_SIZE;
		$offset = ($pageIndex - 1) * $pageSize;
		
		$sql = "SELECT * FROM Task";
		if ($taskName != null && $taskName != '') {
			//$taskName = str_replace(" ", "%", $taskName);
			$sql = $sql . " WHERE title like '%$taskName%'";
		}
		
		$sql = $sql . " ORDER BY create_time desc limit $pageSize 
				offset $offset";
		
		$r = db_fetch_all($sql);
		return $r;
	}
	
	public static function getValidTasks($lng, $lat, $distance, $pageIndex = 1) {
		$pageSize = PAGE_TABLE_SIZE;
		$offset = ($pageIndex - 1) * $pageSize;
		$sql = "SELECT Task.*, GetDistance($lng, $lat, lng, lat) as distance 
			FROM Task 
			WHERE status=1 
			AND start_time < now() 
			and end_time > now() 
			having distance < $distance
				order by distance ASC limit $pageSize 
				offset $offset";
		$r = db_fetch_all($sql);
		return $r;
	}
	
	public static function getMyAcceptTask($openid, $pageIndex = 1) {
		$pageSize = PAGE_TABLE_SIZE;
		$offset = ($pageIndex - 1) * $pageSize;
		$openid_e = sqlstr($openid);
		$sql = "SELECT * FROM Task WHERE accepter_openid=$openid_e
				order by accept_time DESC limit $pageSize 
				offset $offset";
		$r = db_fetch_all($sql);
		return $r;
	}
	
	public static function getMyPublishTask($openid, $pageIndex = 1) {
		$pageSize = PAGE_TABLE_SIZE;
		$offset = ($pageIndex - 1) * $pageSize;
		$openid_e = sqlstr($openid);
		$sql = "SELECT * FROM Task WHERE publisher_openid=$openid_e
				order by create_time DESC limit $pageSize 
				offset $offset";
		$r = db_fetch_all($sql);
		return $r;
	}
	
	public static function getTaskById($id) {
		$sql = "SELECT *FROM Task WHERE id=?";
		bindParams($sql, array($id));
		$r = db_fetch_one($sql);
		return $r;
	}
	
	public static function getPublishTaskCountById($openid) {
		$openid_e = sqlstr($openid);
		$sql = "SELECT COUNT(1) FROM Task WHERE publisher_openid=$openid_e and status=3";
		return db_fetch_value($sql);
	}
	
	public static function getAcceptTaskCountById($openid) {
		$openid_e = sqlstr($openid);
		$sql = "SELECT COUNT(1) FROM Task WHERE accepter_openid=$openid_e and status=3";
		return db_fetch_value($sql);
	}
	
}

class VerifyCodeModel extends Model {
	public static function getCodeByPhone($phone) {
		$sql = "SELECT *FROM VerifyCode WHERE phone=?";
		bindParams($sql, array($phone));
		$r = db_fetch_one($sql);
		return $r;
	} 
	public static function deleteVerifyCode($phone)
	{
		$sql = "DELETE FROM VerifyCode where phone=?";
		bindParams($sql, array($phone));
		return db_execute($sql);
	}
	
	public static function updateOrInsertVerifyCode($phone, $code) {
		$sql = "SELECT count(1) from VerifyCode where phone=?";
		bindParams($sql, array($phone));
		$count = db_fetch_value($sql);
		
		$date = date('Y-m-d H:i:s', time());
		
		if ($count > 0) {
			$sql = "UPDATE VerifyCode set status=0, time=?, code=? where phone=?";
			bindParams($sql, array($date, $code, $phone));
			$r = db_execute($sql);
			return $r;
		}
	
		$sql = "INSERT INTO VerifyCode(phone, code, status, time) VALUES(?, ?, 0, ?)";
		bindParams($sql, array($phone, $code, $date));
		$r = db_execute($sql);
		return $r;
	}
}

?>


