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

 Date: 14/10/2022 15:05:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for task_gdt_syncs
-- ----------------------------
DROP TABLE IF EXISTS `task_gdt_syncs`;
CREATE TABLE `task_gdt_syncs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '父任务id',
  `app_id` varchar(255) NOT NULL DEFAULT '' COMMENT '应用id',
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '账户id',
  `sync_type` varchar(50) NOT NULL DEFAULT '' COMMENT '同步类型',
  `exec_status` varchar(50) NOT NULL DEFAULT '' COMMENT '执行状态',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '账户id',
  `extends` text COMMENT '扩展字段',
  `fail_data` text COMMENT '失败数据',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`) USING BTREE,
  KEY `task_id` (`task_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='头条广告组同步任务表';

SET FOREIGN_KEY_CHECKS = 1;
