<?php

$sql = "
DROP TABLE IF EXISTS `rf_addon_example_curd`;
CREATE TABLE `rf_addon_example_curd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID(单选)',
  `manager_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `sort` int(255) DEFAULT '0' COMMENT '排序',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '推荐位',
  `sex` tinyint(4) NOT NULL DEFAULT '1' COMMENT '性别1男2女',
  `content` text NOT NULL COMMENT '内容',
  `tag` varchar(100) NOT NULL DEFAULT '' COMMENT '标签',
  `cover` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `covers` text NOT NULL COMMENT '图片组',
  `file` varchar(100) NOT NULL DEFAULT '' COMMENT '文件',
  `files` text NOT NULL COMMENT '文件组',
  `attachfile` varchar(100) NOT NULL DEFAULT '' COMMENT '附件',
  `keywords` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `price` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
  `start_time` int(10) DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) DEFAULT '0' COMMENT '结束时间',
  `email` varchar(60) DEFAULT '',
  `provinces` varchar(10) DEFAULT '',
  `city` varchar(10) DEFAULT '',
  `area` varchar(10) DEFAULT '',
  `ip` varchar(16) DEFAULT '' COMMENT 'ip',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_示例插件_curd';

DROP TABLE IF EXISTS `rf_addon_example_cate`;
CREATE TABLE `rf_addon_example_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `sort` int(5) DEFAULT '0' COMMENT '排序',
  `level` tinyint(1) DEFAULT '1' COMMENT '级别',
  `pid` int(50) DEFAULT '0' COMMENT '上级id',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='扩展_示例插件_分类表';
";

// 执行sql
Yii::$app->getDb()->createCommand($sql)->execute();