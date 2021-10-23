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

 Date: 23/10/2021 15:30:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_conversions
-- ----------------------------
DROP TABLE IF EXISTS `gdt_conversions`;
CREATE TABLE `gdt_conversions` (
  `id` varchar(255) NOT NULL COMMENT '广告id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `account_id` varchar(50) NOT NULL COMMENT '账户id',
  `access_type` varchar(50) NOT NULL COMMENT '上报方式',
  `claim_type` varchar(50) NOT NULL COMMENT '归因方式',
  `feedback_url` varchar(512) NOT NULL COMMENT '监控链接',
  `self_attributed` varchar(50) NOT NULL COMMENT '是否自归因',
  `optimization_goal` varchar(50) NOT NULL COMMENT '优化目标类型',
  `deep_behavior_optimization_goal` varchar(50) NOT NULL COMMENT '深度优化行为目标',
  `deep_worth_optimization_goal` varchar(50) NOT NULL COMMENT '深度优化 ROI 目标',
  `user_action_set_id` varchar(255) NOT NULL COMMENT '数据源 id',
  `user_action_set_key` varchar(255) NOT NULL COMMENT 'SDK 接入时对应的密钥',
  `site_set_enable` varchar(50) NOT NULL COMMENT '当前站点是否可用',
  `is_deleted` varchar(50) NOT NULL COMMENT '是否已删除',
  `access_status` varchar(50) NOT NULL COMMENT '接入状态',
  `create_source_type` varchar(50) NOT NULL COMMENT '转化创建来源',
  `app_android_channel_package_id` varchar(255) NOT NULL COMMENT '安卓应用渠道包 id',
  `promoted_object_id` varchar(255) NOT NULL COMMENT '推广目标 id',
  `conversion_scene` varchar(50) NOT NULL COMMENT '转化场景',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `updated_at` (`updated_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通转化归因表';

SET FOREIGN_KEY_CHECKS = 1;
