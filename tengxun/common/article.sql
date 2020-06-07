CREATE TABLE `article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` char(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `test`.`article`
ADD UNIQUE INDEX `phone`(`phone`) USING BTREE;


CREATE TABLE `award`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` char(11) NOT NULL,
  `award` char(3) NOT NULL,
  `create_time` datetime(0) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `phone`(`phone`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE
);
ALTER TABLE `test`.`award`
MODIFY COLUMN `award` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '0手机  3贴纸  6电话卡' AFTER `phone`,
ADD INDEX `award`(`award`) USING BTREE;