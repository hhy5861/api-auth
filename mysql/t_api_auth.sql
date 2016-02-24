/*
 Navicat MySQL Data Transfer

 Source Server         : Cheyian
 Source Server Type    : MySQL
 Source Server Version : 50169
 Source Host           : 192.168.110.30
 Source Database       : d_open_api_auth

 Target Server Type    : MySQL
 Target Server Version : 50169
 File Encoding         : utf-8

 Date: 02/24/2016 17:10:28 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `t_api_auth`
-- ----------------------------
DROP TABLE IF EXISTS `t_api_auth`;
CREATE TABLE `t_api_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rand` char(6) NOT NULL DEFAULT '' COMMENT '随机码',
  `appid` char(32) NOT NULL DEFAULT '' COMMENT 'appid',
  `secretid` varbinary(50) NOT NULL COMMENT 'secretid',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `utime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `module` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册平台模块id',
  `valid` enum('0','1') NOT NULL DEFAULT '0' COMMENT '数据在效性（0：有效，1：无效）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
