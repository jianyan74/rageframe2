<?php

namespace addons\Merchants;

use yii\db\Migration;
use common\enums\AppEnum;
use common\models\common\AuthItemChild;
use common\models\common\AuthRole;
use common\models\common\ConfigValue;
use common\models\merchant\Member;
use common\models\merchant\Merchant;
use common\helpers\MigrateHelper;
use common\interfaces\AddonWidget;

/**
 * 卸载
 *
 * Class UnInstall
 * @package addons\Merchants
 */
class UnInstall extends Migration implements AddonWidget
{
    /**
    * @param $addon
    * @return mixed|void
    * @throws \yii\base\InvalidConfigException
    * @throws \yii\web\NotFoundHttpException
    * @throws \yii\web\UnprocessableEntityHttpException
    */
    public function run($addon)
    {
        // 移除商家角色
        AuthRole::deleteAll(['>', 'merchant_id', 1]);
        // 移除商家权限
        AuthItemChild::deleteAll(['app_id' => AppEnum::MERCHANT]);
        // 清理商家
        Merchant::deleteAll(['>', 'id', 1]);
        // 清理商家所属用户
        Member::deleteAll();
        // 清理配置
        ConfigValue::deleteAll(['>', 'merchant_id', 1]);

        // MigrateHelper::downByPath([
        //     '@addons/Merchants/console/migrations/'
        // ]);
    }
}