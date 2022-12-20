/*
 Navicat Premium Data Transfer

 Source Server         : mariadb
 Source Server Type    : MariaDB
 Source Server Version : 100336
 Source Host           : localhost:3306
 Source Schema         : n8_adv_gdt

 Target Server Type    : MariaDB
 Target Server Version : 100336
 File Encoding         : 65001

 Date: 20/12/2022 11:05:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_account_daily_balance_reports
-- ----------------------------
DROP TABLE IF EXISTS `gdt_account_daily_balance_reports`;
CREATE TABLE `gdt_account_daily_balance_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '广告主id',
  `fund_type` varchar(50) NOT NULL DEFAULT '' COMMENT '资金账户类型',
  `stat_datetime` timestamp NULL DEFAULT NULL COMMENT '数据起始时间',
  `deposit` int(11) NOT NULL DEFAULT 0 COMMENT '总存入',
  `paid` int(11) NOT NULL DEFAULT 0 COMMENT '总支出',
  `trans_in` int(11) NOT NULL DEFAULT 0 COMMENT '总转入',
  `trans_out` int(11) NOT NULL DEFAULT 0 COMMENT '总转出',
  `credit_modify` int(11) NOT NULL DEFAULT 0 COMMENT '授信调整',
  `balance` int(11) NOT NULL DEFAULT 0 COMMENT '日终结余',
  `preauth_balance` int(11) NOT NULL DEFAULT 0 COMMENT '预授权额度',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_datetime_account_id` (`stat_datetime`,`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通账户日结明细';

SET FOREIGN_KEY_CHECKS = 1;
