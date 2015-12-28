<?php
//载入ucpass类
require_once('../smslib/Ucpaas.class.php');

function sendSMS($phone, $code, $templateId)
{
	//发送验证码
	//初始化必填
	$options['accountsid']='ae9fa29bcd06ba08599e14bcc40c777d';
	$options['token']='bbde082ad947d5d867cdbabcf3858c7e';
	//初始化 $options必填
	$ucpass = new Ucpaas($options);
	//开发者账号信息查询默认为json或xml
	//echo $ucpass->getDevinfo('xml');
	//短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
	$appId = "ce27a03259e04de485d9efd82ba70d95";
	$to = $phone;
	$param=$code.',20';
	$result = $ucpass->templateSMS($appId,$to,$templateId,$param);
	return $result;
}

?>

