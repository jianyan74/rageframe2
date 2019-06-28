<?php
namespace addons\RfSignShoppingDay;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\RfSignShoppingDay
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
        DROP TABLE IF EXISTS `rf_addon_sign_shopping_street_award`;
CREATE TABLE `rf_addon_sign_shopping_street_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `cate_id` tinyint(4) DEFAULT '1' COMMENT '分类',
  `sort` int(10) DEFAULT '0' COMMENT '排序',
  `prob` mediumint(7) NOT NULL DEFAULT '0' COMMENT '中奖概率',
  `all_num` int(10) NOT NULL DEFAULT '0' COMMENT '奖品总数量',
  `surplus_num` int(10) NOT NULL DEFAULT '0' COMMENT '奖品剩余数量',
  `max_day_num` int(10) NOT NULL DEFAULT '0' COMMENT '每日限制中奖数',
  `max_user_num` int(10) NOT NULL DEFAULT '0' COMMENT '每人最多中奖数',
  `start_time` int(10) DEFAULT '0' COMMENT '奖品有效开始时间',
  `end_time` int(10) DEFAULT '0' COMMENT '奖品有效结束时间',
  `draw_start_time` int(10) DEFAULT '0' COMMENT '奖品可中开始时间',
  `draw_end_time` int(10) DEFAULT '0' COMMENT '奖品可中结束时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)',
  `created_at` int(10) DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_购物节_奖品表';

-- ----------------------------
-- Table structure for rf_addon_sign_shopping_street_record
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_sign_shopping_street_record`;
CREATE TABLE `rf_addon_sign_shopping_street_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `openid` varchar(50) NOT NULL,
  `is_win` int(2) NOT NULL DEFAULT '2',
  `award_id` varchar(50) DEFAULT NULL,
  `award_title` varchar(255) DEFAULT NULL,
  `award_cate_id` int(11) DEFAULT '0',
  `record_date` date DEFAULT NULL COMMENT '日期',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)',
  `created_at` int(10) DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_购物节_中奖记录表';

-- ----------------------------
-- Table structure for rf_addon_sign_shopping_street_stat
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_sign_shopping_street_stat`;
CREATE TABLE `rf_addon_sign_shopping_street_stat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `source_page` varchar(200) NOT NULL DEFAULT '' COMMENT '来源 url',
  `page` varchar(200) NOT NULL DEFAULT '' COMMENT '当前页面 url',
  `device` varchar(20) NOT NULL DEFAULT '' COMMENT '设备',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT 'ip地址',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)',
  `created_at` int(10) DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_购物节_访问记录表';

-- ----------------------------
-- Table structure for rf_addon_sign_shopping_street_user
-- ----------------------------
DROP TABLE IF EXISTS `rf_addon_sign_shopping_street_user`;
CREATE TABLE `rf_addon_sign_shopping_street_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `realname` varchar(50) DEFAULT NULL,
  `integral` int(11) DEFAULT '0' COMMENT '积分',
  `avatar` varchar(150) NOT NULL COMMENT '头像',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `sign_num` int(10) DEFAULT '0',
  `ip` varchar(15) NOT NULL COMMENT 'IP地址',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)',
  `created_at` int(10) DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_购物节_用户表';
        ";

        // 执行sql
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}