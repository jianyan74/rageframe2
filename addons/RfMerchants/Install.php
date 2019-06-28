<?php
namespace addons\RfMerchants;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\RfMerchants
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
DROP TABLE IF EXISTS `rf_addon_merchant`;
CREATE TABLE `rf_addon_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='插件_多商户';

-- ----------------------------
-- Records of rf_addon_merchant
-- ----------------------------
INSERT INTO `rf_addon_merchant` VALUES ('1', '默认商户', '1', '1557286773', '1557286773');
        ";

        // 执行sql
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}