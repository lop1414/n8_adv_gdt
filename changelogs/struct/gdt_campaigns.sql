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

 Date: 23/02/2022 10:22:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_campaigns
-- ----------------------------
DROP TABLE IF EXISTS `gdt_campaigns`;
CREATE TABLE `gdt_campaigns` (
  `id` varchar(255) NOT NULL COMMENT '计划id',
  `account_id` varchar(50) NOT NULL DEFAULT '' COMMENT '账户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `configured_status` varchar(50) NOT NULL DEFAULT '' COMMENT '客户设置的状态',
  `campaign_type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `promoted_object_type` varchar(50) NOT NULL DEFAULT '' COMMENT '目标类型',
  `total_budget` int(11) DEFAULT '0' COMMENT '总预算',
  `daily_budget` int(11) DEFAULT '0' COMMENT '日预算',
  `is_deleted` varchar(50) NOT NULL DEFAULT '' COMMENT '是否已删除',
  `speed_mode` varchar(50) NOT NULL DEFAULT '' COMMENT '投放速度模式',
  `created_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `last_modified_time` timestamp NULL DEFAULT NULL COMMENT '最后修改时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `create_time` (`created_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通计划信息';

SET FOREIGN_KEY_CHECKS = 1;
