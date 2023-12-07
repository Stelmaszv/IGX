ALTER TABLE `Cats` CHANGE `name` `name` VARCHAR(256) NULL COMMENT '{{0}}';
ALTER TABLE `Cats` CHANGE `counter` `counter` INT(255) NULL COMMENT '{{1}}';
ALTER TABLE `Cats` CHANGE `description` `description` TEXT(100) NULL COMMENT '{{2}}';