<?php

namespace addons\Wechat\common\components;

use Yii;
use common\enums\AppEnum;
use common\helpers\AddonHelper;
use common\interfaces\AddonWidget;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\Wechat\common\config
 */
class Bootstrap implements AddonWidget
{
    /**
    * @param $addon
    * @return mixed|void
    */
    public function run($addon)
    {
        Yii::$app->services->merchant->addId(0);

        // 注册资源
        if (in_array(Yii::$app->id, [AppEnum::MERCHANT, AppEnum::BACKEND])) {
            AddonHelper::filePath();
        }

        /** ------ 微信自定义接口配置------ **/
        Yii::$app->params['userApiPath'] = Yii::getAlias('@root') . '/addons/Wechat/common/userapis'; // 自定义接口路径
        Yii::$app->params['userApiNamespace'] = '\addons\Wechat\common\userapis'; // 命名空间
        Yii::$app->params['userApiCachePrefixKey'] = 'wechat:reply:user-api:'; // 缓存前缀
    }
}