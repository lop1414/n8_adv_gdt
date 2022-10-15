ALTER TABLE `n8_adv_gdt`.`gdt_ad_reports`
    ADD COLUMN `from_follow_uv` int(11) NOT NULL COMMENT '公众号关注人数' AFTER `conversions_count`;
