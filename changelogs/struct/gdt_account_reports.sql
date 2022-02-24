/*
 Navicat Premium Data Transfer

 Source Server         : 虚拟机9.7.2
 Source Server Type    : MySQL
 Source Server Version : 50732
 Source Host           : localhost:3306
 Source Schema         : n8_adv_gdt

 Target Server Type    : MySQL
 Target Server Version : 50732
 File Encoding         : 65001

 Date: 24/02/2022 10:54:08
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_account_reports
-- ----------------------------
DROP TABLE IF EXISTS `gdt_account_reports`;
CREATE TABLE `gdt_account_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '广告主id',
  `stat_datetime` timestamp NULL DEFAULT NULL COMMENT '数据起始时间',
  `cost` int(11) NOT NULL DEFAULT '0' COMMENT '花费',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '曝光量',
  `valid_click_count` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `conversions_count` int(11) NOT NULL DEFAULT '0' COMMENT '目标转化量',
  `extends` text COMMENT '扩展字段',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_datetime_account_id` (`stat_datetime`,`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通广告主数据报表';

SET FOREIGN_KEY_CHECKS = 1;
