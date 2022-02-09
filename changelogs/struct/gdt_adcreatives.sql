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

 Date: 22/10/2021 15:29:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_adcreatives
-- ----------------------------
DROP TABLE IF EXISTS `gdt_adcreatives`;
CREATE TABLE `gdt_adcreatives` (
  `id` varchar(255) NOT NULL COMMENT '广告创意id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `account_id` varchar(50) NOT NULL COMMENT '账户id',
  `campaign_id` varchar(255) NOT NULL COMMENT '计划id',
  `adcreative_template_id` varchar(50) NOT NULL COMMENT '创意形式 id',
  `page_type` varchar(50) NOT NULL COMMENT '落地页类型',
  `link_page_type` varchar(50) NOT NULL COMMENT '文字链跳转类型类型',
  `link_name_type` varchar(50) NOT NULL COMMENT '文字链接名称类型',
  `conversion_target_type` varchar(50) NOT NULL COMMENT '数据展示转化行为',
  `site_set` tinytext COMMENT '投放版位集合',
  `automatic_site_enabled` varchar(50) NOT NULL DEFAULT '' COMMENT '是否开启自动版位功能',
  `promoted_object_type` varchar(50) NOT NULL DEFAULT '' COMMENT '推广目标类型',
  `promoted_object_id` varchar(50) NOT NULL DEFAULT '' COMMENT '推广目标 id',
  `is_deleted` varchar(50) NOT NULL DEFAULT '' COMMENT '是否已删除',
  `is_dynamic_creative` varchar(50) NOT NULL DEFAULT '' COMMENT '是否是动态创意广告自动生成的',
  `component_id` varchar(50) NOT NULL DEFAULT '' COMMENT '附加创意组件 id',
  `enable_breakthrough_siteset` varchar(50) NOT NULL DEFAULT '' COMMENT '是否支持版位突破',
  `creative_template_version_type` varchar(50) NOT NULL DEFAULT '' COMMENT '数据版本类型 0:历史数据 1:旧版本 2:新版本',
  `created_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `last_modified_time` timestamp NULL DEFAULT NULL COMMENT '最后修改时间',
  `extends` text COMMENT '扩展字段',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `campaign_id` (`campaign_id`) USING BTREE,
  KEY `created_time` (`created_time`) USING BTREE,
  KEY `last_modified_time` (`last_modified_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通广告创意表';

SET FOREIGN_KEY_CHECKS = 1;
