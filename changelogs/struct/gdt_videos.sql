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

 Date: 13/10/2022 10:58:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_videos
-- ----------------------------
DROP TABLE IF EXISTS `gdt_videos`;
CREATE TABLE `gdt_videos` (
  `id` varchar(128) NOT NULL DEFAULT '' COMMENT '视频id',
  `file_size` varchar(100) NOT NULL DEFAULT '' COMMENT '视频大小',
  `width` int(11) NOT NULL DEFAULT '0' COMMENT '视频宽度',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT '视频高度',
  `video_codec` varchar(255) NOT NULL DEFAULT '' COMMENT '视频格式',
  `signature` varchar(64) NOT NULL DEFAULT '' COMMENT '视频签名',
  `key_frame_image_url` varchar(512) NOT NULL DEFAULT '' COMMENT '视频首帧截图',
  `video_bit_rate` varchar(100) NOT NULL DEFAULT '' COMMENT '码率',
  `image_duration_millisecond` float NOT NULL DEFAULT '0' COMMENT '视频时长',
  `source_type` varchar(50) NOT NULL DEFAULT '' COMMENT '素材来源',
  `created_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '素材文件名',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `signature` (`signature`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通视频表';

SET FOREIGN_KEY_CHECKS = 1;
