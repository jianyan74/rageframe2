<?php
namespace addons\RfExample;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\RfExample
 */
class Install implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function run($addon)
    {
        $sql = "
DROP TABLE IF EXISTS `rf_addon_example_curd`;
CREATE TABLE `rf_addon_example_curd`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `merchant_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '商户id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `cate_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类ID(单选)',
  `manager_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `sort` int(10) NULL DEFAULT 0 COMMENT '排序',
  `position` int(11) NOT NULL DEFAULT 0 COMMENT '推荐位',
  `sex` tinyint(4) NOT NULL DEFAULT 1 COMMENT '性别1男2女',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `tag` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `cover` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `covers` json NOT NULL COMMENT '图片组',
  `file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件',
  `files` json NOT NULL COMMENT '文件组',
  `attachfile` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '附件',
  `keywords` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `price` float(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '价格',
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击',
  `start_time` int(10) NULL DEFAULT 0 COMMENT '开始时间',
  `end_time` int(10) NULL DEFAULT 0 COMMENT '结束时间',
  `email` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `provinces` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `city` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `area` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `ip` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'ip',
  `date` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `time` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '颜色',
  `address` json NOT NULL COMMENT '图片组',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '状态',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '扩展_示例插件_curd' ROW_FORMAT = Compact;

DROP TABLE IF EXISTS `rf_addon_example_cate`;
CREATE TABLE `rf_addon_example_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `sort` int(5) DEFAULT '0' COMMENT '排序',
  `level` tinyint(1) DEFAULT '1' COMMENT '级别',
  `pid` int(50) DEFAULT '0' COMMENT '上级id',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `tree` varchar(500) NOT NULL DEFAULT '' COMMENT '树',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='扩展_示例插件_分类表';
";

        // 执行sql
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}