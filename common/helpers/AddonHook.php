<?php
namespace common\helpers;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class AddonHook
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonHook
{
    public $layout = null;

    /**
     * 默认钩子渲染控制器
     *
     * @var string
     */
    const hookPath = 'setting/';

    /**
     * 实例化钩子
     *
     * @param string $addonsName 模块名称
     * @param array $params 传递参数
     * @param string $action 默认钩子方法
     * @param bool $debug 是否开启报错
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function to($addonsName, $params = [], $action = 'hook', $debug = false)
    {
        try
        {
            $oldAddonInfo = Yii::$app->params['addonInfo'];
            $oldAddon = Yii::$app->params['addon'];
            $oldAddonBinding = Yii::$app->params['addonBinding'];

            // 初始化模块
            AddonHelper::initAddon($addonsName, self::hookPath . $action);
            // 解析路由
            AddonHelper::analysisRoute(self::hookPath . $action, 'backend');

            $class = Yii::$app->params['addonInfo']['controllersPath'];
            $controllerName = Yii::$app->params['addonInfo']['controllerName'];
            $actionName = Yii::$app->params['addonInfo']['actionName'];
            Yii::$app->params['addonInfo']['moduleId'] = '';

            // 实例化解获取数据
            $list = new $class($controllerName, Yii::$app->module);
            $data = $list->$actionName($params);

            // 恢复存储信息
            Yii::$app->params['addonInfo'] = $oldAddonInfo;
            Yii::$app->params['addon'] = $oldAddon;
            Yii::$app->params['addonBinding'] = $oldAddonBinding;

            return $data;
        }
        catch (\Exception $e)
        {
            Yii::error($e->getMessage());

            if (YII_DEBUG || $debug)
            {
                throw new NotFoundHttpException($e->getMessage());
            }

            return false;
        }
    }
}