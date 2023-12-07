ALTER TABLE `User` CHANGE `name` `name` VARCHAR(150) NULL COMMENT '{{0}}';
ALTER TABLE `User` CHANGE `password` `password` VARCHAR(150) NULL COMMENT '{{1}}';
ALTER TABLE `User` CHANGE `email` `email` VARCHAR(150) NULL COMMENT '{{2}}';
ALTER TABLE `User` CHANGE `roles` `roles` JSON NULL COMMENT '{{3}}';
ALTER TABLE `User` CHANGE `salt` `salt` VARCHAR(200) NULL COMMENT '{{4}}';