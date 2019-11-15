<?php

namespace addons\RfWechat\common\components;

use common\helpers\AddonHelper;
use Yii;
use common\enums\AppEnum;
use common\interfaces\AddonWidget;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\RfWechat\common\config
 */
class Bootstrap implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function run($addon)
    {
        // 动态注入服务
        Yii::$app->set('wechatServices', [
            'class' => 'addons\RfWechat\services\Application',
        ]);

        // 注册资源
        if (in_array(Yii::$app->id, [AppEnum::MERCHANT, AppEnum::BACKEND])) {
            AddonHelper::filePath();
        }
    }
}