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

 Date: 21/02/2022 10:58:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gdt_adgroup_extends
-- ----------------------------
DROP TABLE IF EXISTS `gdt_adgroup_extends`;
CREATE TABLE `gdt_adgroup_extends` (
  `adgroup_id` varchar(100) NOT NULL DEFAULT '' COMMENT '广告组id',
  `convert_callback_strategy_id` int(11) NOT NULL DEFAULT '0' COMMENT '回传策略id',
  `convert_callback_strategy_group_id` int(11) NOT NULL DEFAULT '0' COMMENT '回传策略组id',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`adgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广电通广告组扩展表';

SET FOREIGN_KEY_CHECKS = 1;
