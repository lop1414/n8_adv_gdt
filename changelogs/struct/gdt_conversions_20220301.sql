ALTER TABLE `n8_adv_gdt`.`gdt_conversions`
    MODIFY COLUMN `feedback_url` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '监控链接' AFTER `claim_type`;
