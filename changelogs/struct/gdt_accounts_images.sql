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

 Date: 22/10/2021 16:22:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_accounts_images
-- ----------------------------
DROP TABLE IF EXISTS `gdt_accounts_images`;
CREATE TABLE `gdt_accounts_images` (
  `account_id` varchar(50) NOT NULL DEFAULT '' COMMENT '账户id',
  `image_id` varchar(128) NOT NULL DEFAULT '' COMMENT '视频id',
  UNIQUE KEY `account_image` (`account_id`,`image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通账户-图片关联表';

SET FOREIGN_KEY_CHECKS = 1;
