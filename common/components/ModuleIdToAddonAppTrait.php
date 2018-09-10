<?php
namespace common\components;

use Yii;

/**
 * 模块ID转插件前缀
 *
 * Trait ModuleIdToAddonAppTrait
 * @package common\components
 */
trait ModuleIdToAddonAppTrait
{
    /**
     * 判断应用类别返回模块路径前缀
     *
     * @return mixed
     */
    protected function getModuleIdToAddonApp()
    {
        $appId = [
            "app-backend" => 'backend',
            "app-frontend" => 'frontend',
            "app-wechat" => 'wechat',
            "app-api" => 'api',
        ];

        $moduleId = Yii::$app->controller->module->id;

        // 判断如果是模块进入之前返回模块所在的应用id
        if ($moduleId == 'addons')
        {
            return Yii::$app->params['addonInfo']['moduleId'];
        }

        Yii::$app->params['addonInfo']['moduleId'] = $appId[$moduleId];
        return $appId[$moduleId];
    }
}