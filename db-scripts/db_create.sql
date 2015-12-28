-- CREATE DATABASE app_task;
-- 创建自定义函数，经纬度距离计算
set global log_bin_trust_function_creators = 1;
DROP FUNCTION IF EXIST `GetDistance`;
DELIMITER $$
CREATE FUNCTION `GetDistance`
( 
GPSLng DECIMAL(12,6),
GPSLat DECIMAL(12,6),
Lng  DECIMAL(12,6),
Lat DECIMAL(12,6)
)
RETURNS DECIMAL(12,4)

BEGIN
   DECLARE result DECIMAL(12,4);
   SET result=6371.004*ACOS(SIN(GPSLat/180*PI())*SIN(Lat/180*PI())+COS(GPSLat/180*PI())*COS(Lat/180*PI())*COS((GPSLng-Lng)/180*PI()));
   RETURN result;
END $$
DELIMITER $$

--
-- 表的结构 `account`
--

DROP TABLE IF EXISTS `Account`;
CREATE TABLE IF NOT EXISTS `Account` (
  `pid`  bigint(20) NOT NULL AUTO_INCREMENT,
  `phone` varchar(32) DEFAULT NULL COMMENT '手机号码',
  `openid` varchar(64) NOT NULL COMMENT 'openid',
  `nickname` varchar(64) NOT NULL DEFAULT '客官' COMMENT '妮称',
  `user_scheme` int(11) DEFAULT 0 COMMENT '用户状态，1代表已认证',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间（不要修改）',
  PRIMARY KEY `pid`(`pid`),
  UNIQUE KEY `openid`(`openid`)
)AUTO_INCREMENT=108642555 ;
ALTER TABLE `Account` ADD COLUMN `phone` varchar(32) DEFAULT NULL COMMENT '手机号';

--
-- 表的结构 `accountExtra`
--

DROP TABLE IF EXISTS `AccountExtra`;
CREATE TABLE IF NOT EXISTS `AccountExtra` (
  `openid` varchar(64) NOT NULL COMMENT 'openid',
  `icon`	varchar(64) NOT NULL COMMENT '工作照片',
  `address` varchar(256) NOT NULL COMMENT '地址',
  `fullname` varchar(32) NOT NULL COMMENT '直实姓名',
  `idcard`	varchar(32) NOT NULL COMMENT '身份证号',
  `contact`  varchar(32) NOT NULL COMMENT '紧急联系人姓名',
  `contact_phone`  varchar(32) NOT NULL COMMENT '紧急联系人电话',
  `card_pic` varchar(256) NOT NULL COMMENT '身份证图片地址，逗号分割',
  `inviter`  varchar(32) DEFAULT NULL COMMENT '邀请人',
  `status`	int(11) DEFAULT 0 COMMENT '0：申请中,1申请成功,-1:失败',
  `info`	varchar(128) DEFAULT NULL COMMENT '审核结果',
  `create_time` timestamp DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间（不要修改）',
  `modified_time` timestamp not null COMMENT '修改',
  PRIMARY KEY `openid`(`openid`)
)AUTO_INCREMENT=108642555 ;


-- 评价体系
DROP TABLE IF EXISTS `Reputation`;
CREATE TABLE IF NOT EXISTS `Reputation` (
  `phone` varchar(32) NOT NULL COMMENT '手机号码惟一',
  `publish_good_count` int(11) NOT NULL DEFAULT 0 COMMENT '发布任务获得好评数，如果接任务的人不主动评默认好评',
  `publish_weak_count` int(11) NOT NULL DEFAULT 0 COMMENT '发布任务获得差评数',
  `publish_complaint_count` int(11) NOT NULL DEFAULT 0 COMMENT '发布任务被投诉次数', 
  `accept_good_count` int(11) NOT NULL DEFAULT 0 COMMENT '接任务获得好评数，如果发任务的人不主动评默认好评',
  `accept_weak_count` int(11) NOT NULL DEFAULT 0 COMMENT '接任务都获得差评数',
  `accept_complaint_count` int(11) NOT NULL DEFAULT 0 COMMENT '接任务被投诉次数',
  UNIQUE KEY `phone`(`phone`)
);

-- 任务
DROP TABLE IF EXISTS `Task`;
CREATE TABLE IF NOT EXISTS `Task` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '任务ID',
  `publisher_openid` varchar(64) NOT NULL COMMENT '发布者openid',
  `publisher_phone` varchar(32) NOT NULL COMMENT '发布者手机号',
  `title` varchar(32) NOT NULL COMMENT '任务标题',
  `description` longtext NOT NULL DEFAULT '' COMMENT '发布任务的描述',
  `reward` int(11) NOT NULL DEFAULT 0 COMMENT '奖励多少钱单位分',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '任务状态，初始化1',
  `address` varchar(128) DEFAULT NULL COMMENT '任务地址，可选',
  `start_time` datetime NOT NULL COMMENT '任务开始时间',
  `end_time` datetime NOT NULL COMMENT '任务结束时间',
  `accepter_phone` varchar(32) DEFAULT NULL COMMENT '接任务者手机号',
  `accepter_openid` varchar(64) DEFAULT NULL COMMENT '接受者openid',
  `finish_time` datetime DEFAULT NULL COMMENT '完成任务时间（不要修改）',
  `accept_time` datetime DEFAULT NULL COMMENT '接任务时间（不要修改）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间（不要修改）',
  `from_address` varchar(128) DEFAULT NULL COMMENT '从哪里取的地址，可选',
  `lng` DECIMAL(12,6) DEFAULT NULL COMMENT '经度',
  `lat` DECIMAL(12,6) DEFAULT NULL COMMENT '纬度',
  KEY `publisher_phone`(`publisher_phone`),
  KEY `accepter_phone`(`accepter_phone`),
  KEY `publisher_openid`(`publisher_openid`),
  KEY `accepter_openid`(`accepter_openid`),
  PRIMARY KEY `id`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1;
ALTER TABLE `Task` ADD COLUMN `publisher_name` varchar(64) NOT NULL COMMENT '发布者名字';

-- 验证码,每个手机号同一时刻只存一个验证码
DROP TABLE IF EXISTS `VerifyCode`;
CREATE TABLE IF NOT EXISTS `VerifyCode` (
	`phone` varchar(32) NOT NULL COMMENT '手机号',
	`code`	varchar(16) NOT NULL COMMENT '验证码',
	`status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '验证码状态，未使用为0，已使用为1',
	`time`  datetime NOT NULL COMMENT '验证码生成时间'
);



