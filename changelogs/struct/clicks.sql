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

 Date: 25/10/2021 15:39:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clicks
-- ----------------------------
DROP TABLE IF EXISTS `clicks`;
CREATE TABLE `clicks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `click_source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `campaign_id` varchar(255) NOT NULL COMMENT '计划id',
  `adgroup_id` varchar(255) NOT NULL COMMENT '广告组id',
  `ad_id` varchar(255) NOT NULL COMMENT '广告id',
  `adv_click_id` varchar(100) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道id',
  `encrypted_position_id` varchar(100) NOT NULL DEFAULT '0' COMMENT '联盟广告位id',
  `ad_platform_type` varchar(100) NOT NULL DEFAULT '' COMMENT '广告投放平台',
  `muid` varchar(100) NOT NULL DEFAULT '' COMMENT '安卓为IMEI, IOS为IDFA',
  `android_id` varchar(100) NOT NULL DEFAULT '' COMMENT '安卓id',
  `oaid_md5` varchar(64) DEFAULT NULL COMMENT 'Android Q及更高版本的设备号的md5摘要',
  `os` varchar(50) NOT NULL DEFAULT '' COMMENT '操作系统平台',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `ua` varchar(1024) NOT NULL DEFAULT '' COMMENT 'user agent',
  `click_at` timestamp NULL DEFAULT NULL COMMENT '点击时间',
  `callback` text NOT NULL COMMENT '效果数据回传URL',
  `link` text COMMENT '落地页原始url',
  `extends` text NOT NULL COMMENT '扩展字段',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `muid` (`muid`) USING BTREE,
  KEY `ip` (`ip`) USING BTREE,
  KEY `click_at` (`click_at`) USING BTREE,
  KEY `channel_id` (`channel_id`) USING BTREE,
  KEY `request_id` (`request_id`) USING BTREE,
  KEY `adgroup_id` (`adgroup_id`) USING BTREE,
  KEY `oaid_md5` (`oaid_md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通点击表';

SET FOREIGN_KEY_CHECKS = 1;
