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

 Date: 21/10/2021 14:46:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_adgroups
-- ----------------------------
DROP TABLE IF EXISTS `gdt_adgroups`;
CREATE TABLE `gdt_adgroups` (
  `id` varchar(255) NOT NULL COMMENT '广告组id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `account_id` varchar(50) NOT NULL COMMENT '账户id',
  `campaign_id` varchar(255) NOT NULL COMMENT '计划id',
  `site_set` tinytext COMMENT '投放版位集合',
  `optimization_goal` varchar(50) NOT NULL COMMENT '广告优化目标类型',
  `bid_mode` varchar(50) DEFAULT NULL COMMENT '出价方式',
  `bid_amount` int(11) DEFAULT '0' COMMENT '出价',
  `daily_budget` int(11) DEFAULT '0' COMMENT '广告组日预算',
  `configured_status` varchar(50) NOT NULL COMMENT '客户设置的状态',
  `bid_strategy` varchar(50) DEFAULT NULL COMMENT '出价策略',
  `auto_audience` varchar(50) NOT NULL DEFAULT '' COMMENT '是否使用系统优选',
  `conversion_id` int(11) DEFAULT NULL COMMENT '转化目标id',
  `system_status` varchar(50) NOT NULL COMMENT '广告组在系统中的状态',
  `status` varchar(50) NOT NULL COMMENT '广告状态',
  `smart_bid_type` varchar(50) NOT NULL COMMENT '出价类型',
  `is_deleted` varchar(50) NOT NULL DEFAULT '' COMMENT '是否已删除',
  `created_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `last_modified_time` timestamp NULL DEFAULT NULL COMMENT '最后修改时间',
  `extends` text COMMENT '扩展字段',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `campaign_id` (`campaign_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `configured_status` (`configured_status`) USING BTREE,
  KEY `created_time` (`created_time`) USING BTREE,
  KEY `last_modified_time` (`last_modified_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通广告组表';

SET FOREIGN_KEY_CHECKS = 1;
