ALTER TABLE `n8_adv_gdt`.`gdt_ad_reports`
ADD COLUMN `biz_follow_uv` int(11) NOT NULL COMMENT '公众号关注人数(平台上报)' AFTER `from_follow_uv`,
ADD COLUMN `biz_consult_count` int(11) NOT NULL COMMENT '公众号内发消息人数' AFTER `biz_follow_uv`;
