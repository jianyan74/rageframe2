<?php

namespace common\helpers;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class Hook
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class Hook
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
     * @throws \yii\base\InvalidConfigException
     */
    public static function to($addonsName, $params = [], $action = 'hook', $debug = false)
    {
        try {
            $oldAddonInfo = Yii::$app->params['addonInfo'] ?? [];
            $oldAddon = Yii::$app->params['addon'] ?? [];
            $oldAddonBinding = Yii::$app->params['addonBinding'] ?? [];

            // 初始化模块
            AddonHelper::initAddon($addonsName, self::hookPath . $action);
            // 解析路由
            AddonHelper::analysisRoute(self::hookPath . $action, 'backend');

            $class = Yii::$app->params['addonInfo']['controllersPath'];
            $controllerName = Yii::$app->params['addonInfo']['controllerName'];
            $actionName = Yii::$app->params['addonInfo']['actionName'];

            // 实例化解获取数据
            $list = new $class($controllerName, Yii::$app->module);
            $list->layout = null;
            $list->isHook = true;
            $data = $list->$actionName($params);

            // 恢复存储信息
            Yii::$app->params['addonInfo'] = $oldAddonInfo;
            Yii::$app->params['addon'] = $oldAddon;
            Yii::$app->params['addonBinding'] = $oldAddonBinding;

            unset($list);
            return $data;
        } catch (\Exception $e) {
            // 记录到报错日志
            Yii::$app->services->log->setStatusCode(500);
            Yii::$app->services->log->setStatusText('hookError');
            Yii::$app->services->log->setErrData($e->getMessage());
            Yii::$app->services->log->insertLog();

            if (YII_DEBUG || $debug) {
                throw new NotFoundHttpException($e->getMessage());
            }

            return false;
        }
    }
}