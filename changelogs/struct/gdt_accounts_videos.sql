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

 Date: 13/10/2022 11:26:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_accounts_videos
-- ----------------------------
DROP TABLE IF EXISTS `gdt_accounts_videos`;
CREATE TABLE `gdt_accounts_videos` (
  `account_id` varchar(128) NOT NULL DEFAULT '' COMMENT '账户id',
  `video_id` varchar(128) NOT NULL DEFAULT '' COMMENT '视频id',
  `status` varchar(50) NOT NULL COMMENT '视频状态',
  UNIQUE KEY `account_video` (`account_id`,`video_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通账户-视频关联表';

SET FOREIGN_KEY_CHECKS = 1;
