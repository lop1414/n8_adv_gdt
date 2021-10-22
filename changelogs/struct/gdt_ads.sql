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

 Date: 22/10/2021 17:26:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_ads
-- ----------------------------
DROP TABLE IF EXISTS `gdt_ads`;
CREATE TABLE `gdt_ads` (
  `id` varchar(255) NOT NULL COMMENT '广告id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `account_id` varchar(50) NOT NULL COMMENT '账户id',
  `campaign_id` varchar(255) NOT NULL COMMENT '计划id',
  `adgroup_id` varchar(255) NOT NULL COMMENT '广告组 id',
  `adcreative_id` varchar(255) NOT NULL COMMENT '广告创意 id',
  `adcreative_template_id` varchar(50) NOT NULL COMMENT '创意形式 id',
  `configured_status` varchar(50) NOT NULL COMMENT '客户设置的状态',
  `system_status` varchar(50) NOT NULL COMMENT '系统状态',
  `audit_spec` text COMMENT '多版位的审核结果信息',
  `click_tracking_url` varchar(512) NOT NULL COMMENT '点击监控地址',
  `is_deleted` varchar(50) NOT NULL COMMENT '是否已删除',
  `is_dynamic_creative` varchar(50) NOT NULL COMMENT '是否是动态创意广告自动生成的',
  `created_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `last_modified_time` timestamp NULL DEFAULT NULL COMMENT '最后修改时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `adcreative_id` (`adcreative_id`) USING BTREE,
  KEY `created_time` (`created_time`) USING BTREE,
  KEY `last_modified_time` (`last_modified_time`) USING BTREE,
  KEY `adgroup_id` (`adgroup_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通广告表';

SET FOREIGN_KEY_CHECKS = 1;
