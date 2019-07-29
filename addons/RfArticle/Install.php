<?php
namespace addons\RfArticle;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\RfArticle
 */
class Install implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     */
    public function run($addon)
    {
        $sql = "
        DROP TABLE IF EXISTS `rf_addon_article`;
CREATE TABLE `rf_addon_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `cover` varchar(100) DEFAULT '' COMMENT '封面',
  `seo_key` varchar(50) DEFAULT '' COMMENT 'seo关键字',
  `seo_content` varchar(1000) DEFAULT '' COMMENT 'seo内容',
  `cate_id` int(10) DEFAULT '0' COMMENT '分类id',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `position` smallint(5) NOT NULL DEFAULT '0' COMMENT '推荐位',
  `content` longtext COMMENT '文章内容',
  `link` varchar(100) DEFAULT '' COMMENT '外链',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `view` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章表';

-- ----------------------------
-- Table structure for rf_addon_article_adv
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_article_adv`;
CREATE TABLE `rf_addon_article_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(100) DEFAULT '' COMMENT '图片',
  `location_id` int(11) DEFAULT '0' COMMENT '广告位ID',
  `silder_text` varchar(150) DEFAULT '' COMMENT '图片描述',
  `start_time` int(10) DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) DEFAULT '0' COMMENT '结束时间',
  `jump_link` varchar(150) DEFAULT '' COMMENT '跳转链接',
  `jump_type` tinyint(4) DEFAULT '1' COMMENT '跳转方式[1:新标签; 2:当前页]',
  `sort` int(10) DEFAULT '0' COMMENT '优先级',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章_幻灯片表';

-- ----------------------------
-- Table structure for rf_addon_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_article_cate`;
CREATE TABLE `rf_addon_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `sort` int(5) DEFAULT '0' COMMENT '排序',
  `level` tinyint(1) DEFAULT '1' COMMENT '级别',
  `pid` int(50) DEFAULT '0' COMMENT '上级id',
  `tree` varchar(500) NOT NULL DEFAULT '' COMMENT '树',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章分类表';

-- ----------------------------
-- Table structure for rf_addon_article_single
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_article_single`;
CREATE TABLE `rf_addon_article_single` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `name` char(40) DEFAULT '' COMMENT '标识',
  `seo_key` varchar(50) DEFAULT '' COMMENT 'seo关键字',
  `seo_content` varchar(1000) DEFAULT '' COMMENT 'seo内容',
  `cover` varchar(100) DEFAULT '' COMMENT '封面',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `content` longtext COMMENT '文章内容',
  `link` varchar(100) DEFAULT '' COMMENT '外链',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章单页表';

-- ----------------------------
-- Table structure for rf_addon_article_tag
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_article_tag`;
CREATE TABLE `rf_addon_article_tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '标题',
  `sort` int(10) DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `tag_id` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章标签表';

-- ----------------------------
-- Table structure for rf_addon_article_tag_map
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_article_tag_map`;
CREATE TABLE `rf_addon_article_tag_map` (
  `tag_id` int(10) DEFAULT '0' COMMENT '标签id',
  `article_id` int(10) DEFAULT '0' COMMENT '文章id',
  KEY `tag_id` (`tag_id`) USING BTREE,
  KEY `article_id` (`article_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章标签关联表';
        ";

        // 执行sql
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}