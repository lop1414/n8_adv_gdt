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

 Date: 22/10/2021 16:12:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_images
-- ----------------------------
DROP TABLE IF EXISTS `gdt_images`;
CREATE TABLE `gdt_images` (
  `id` varchar(128) NOT NULL COMMENT '图片id',
  `width` int(11) NOT NULL DEFAULT '0' COMMENT '视频宽度',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT '视频高度',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '图片类型',
  `signature` varchar(64) NOT NULL DEFAULT '' COMMENT '图片文件签名',
  `preview_url` varchar(512) NOT NULL DEFAULT '' COMMENT '预览地址',
  `source_type` varchar(50) NOT NULL DEFAULT '' COMMENT '图片来源',
  `image_usage` varchar(50) NOT NULL DEFAULT '' COMMENT '图片用途',
  `owner_account_id` varchar(50) NOT NULL DEFAULT '' COMMENT '图片用途',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '图片状态',
  `created_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `last_modified_time` timestamp NULL DEFAULT NULL COMMENT '最后修改时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `signature` (`signature`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通图片表';

SET FOREIGN_KEY_CHECKS = 1;
