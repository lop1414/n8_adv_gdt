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

 Date: 14/10/2022 11:20:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for task_gdt_video_uploads
-- ----------------------------
DROP TABLE IF EXISTS `task_gdt_video_uploads`;
CREATE TABLE `task_gdt_video_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '父任务id',
  `app_id` varchar(255) NOT NULL DEFAULT '' COMMENT '应用id',
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '账户id',
  `n8_material_video_id` int(11) NOT NULL DEFAULT '0' COMMENT 'n8素材系统视频id',
  `n8_material_video_path` varchar(512) NOT NULL DEFAULT '' COMMENT 'n8素材系统视频地址',
  `n8_material_video_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'n8素材系统视频名称',
  `n8_material_video_signature` varchar(64) NOT NULL DEFAULT '' COMMENT 'n8素材系统视频签名',
  `exec_status` varchar(50) NOT NULL DEFAULT '' COMMENT '执行状态',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `extends` text COMMENT '扩展字段',
  `fail_data` text COMMENT '失败数据',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`) USING BTREE,
  KEY `task_id` (`task_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广点通视频上传任务表';

SET FOREIGN_KEY_CHECKS = 1;
