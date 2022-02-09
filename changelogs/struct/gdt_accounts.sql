/*
 Navicat Premium Data Transfer

 Source Server         : 虚拟机 192.168.20.10
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : localhost:3306
 Source Schema         : n8_adv_gdt

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 21/10/2021 14:45:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_accounts
-- ----------------------------
DROP TABLE IF EXISTS `gdt_accounts`;
CREATE TABLE `gdt_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(50) NOT NULL DEFAULT '' COMMENT '应用id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `company` varchar(100) NOT NULL DEFAULT '' COMMENT '公司',
  `account_id` varchar(50) NOT NULL DEFAULT '' COMMENT '广告账户id',
  `access_token` varchar(50) NOT NULL DEFAULT '',
  `refresh_token` varchar(50) NOT NULL DEFAULT '',
  `fail_at` timestamp NULL DEFAULT NULL COMMENT 'token 过期时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `extend` text COMMENT '扩展字段',
  `parent_id` varchar(50) DEFAULT NULL COMMENT '父级id',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '状态',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `ad` (`app_id`,`account_id`) USING BTREE,
  KEY `fail_at` (`fail_at`) USING BTREE,
  KEY `account_id` (`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通账户信息';

SET FOREIGN_KEY_CHECKS = 1;
