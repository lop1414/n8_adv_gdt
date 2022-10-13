ALTER TABLE `n8_adv_gdt`.`gdt_accounts_images`
    ADD COLUMN `status` varchar(50) NOT NULL COMMENT '图片状态' AFTER `image_id`;
