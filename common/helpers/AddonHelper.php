<?php
namespace common\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\sys\Addons;

/**
 * Class AddonHelper
 * @package common\helpers
 */
class AddonHelper
{
    /**
     * @var
     */
    private static $resourcesUrl;

    /**
     * @var array
     */
    protected static $addonModels = [];

    /**
     * 获取插件配置
     *
     * @param $name
     * @return string
     */
    public static function getAddonConfig($name)
    {
        $class = "addons\\{$name}\\AddonConfig";
        return $class;
    }

    /**
     * 获取插件微信消息配置
     *
     * @param $name
     * @return string
     */
    public static function getAddonMessage($name)
    {
        $class = "addons\\{$name}\\AddonMessage";
        return $class;
    }

    /**
     * 获取插件的根目录目录
     *
     * @param $name
     * @return string
     */
    public static function getAddonRootPath($name)
    {
        return Yii::getAlias('@addons') . "/{$name}/";
    }

    /**
     * @param $name
     * @return string
     */
    public static function getAddonIcon($name)
    {
        return '/backend/resources/img/icon.jpg';
    }

    /**
     * 获取生成asset的资源文件目录
     *
     * @return string
     */
    public static function getResourcesUrl()
    {
        if (!self::$resourcesUrl)
        {
             self::$resourcesUrl = Yii::$app->view->assetBundles[Yii::$app->params['addonInfo']['assetBundlesName']]->baseUrl . '/';
        }

        return self::$resourcesUrl;
    }

    /**
     * 获取资源文件
     *
     * @return string
     */
    public static function getResourcesFile($path)
    {
        return self::getResourcesUrl() . $path;
    }

    /**
     * 获取配置信息
     *
     * @return mixed
     */
    public static function getConfig()
    {
        $model = Yii::$app->params['addon'];
        return unserialize($model->config);
    }

    /**
     * 写入配置信息
     *
     * @param $config
     * @return bool
     */
    public static function setConfig($config)
    {
        $model = Yii::$app->params['addon'];
        $model->config = serialize($config);

        return $model->save();
    }

    /**
     * 获取模块的App路径名称
     *
     * @return mixed|string
     */
    public static function getAppName()
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
            return !empty(Yii::$app->params['addonInfo']['moduleId']) ? Yii::$app->params['addonInfo']['moduleId'] : 'backend';
        }

        Yii::$app->params['addonInfo']['moduleId'] = $appId[$moduleId];
        return isset($appId[$moduleId]) ? $appId[$moduleId] : 'backend';
    }

    /**
     * 初始化模块信息
     *
     * @param string $addonName 模块名称
     * @param string $route 路由
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function initAddon($addonName, $route)
    {
        if (!$addonName)
        {
            throw new NotFoundHttpException("模块不能为空");
        }

        // 减少模块内多次调用hook的查询
        if (isset(self::$addonModels[$addonName]))
        {
            $addonModel = self::$addonModels[$addonName];
        }
        else
        {
            // 获取缓存
            if (!($addonModel = Yii::$app->cache->get('sys-addons:' . $addonName)))
            {
                if (!($addonModel = Addons::findByName($addonName)))
                {
                    throw new NotFoundHttpException("模块不存在");
                }

                // 数据库依赖缓存
                $dependency = new \yii\caching\DbDependency([
                    'sql' => Addons::find()
                        ->select('updated_at')
                        ->where(['name' => $addonName])
                        ->createCommand()
                        ->getRawSql(),
                ]);

                Yii::$app->cache->set('sys-addons:' . $addonName, $addonModel, 360, $dependency);
            }

            self::$addonModels[$addonName] = $addonModel;
        }

        // 当前模块实例
        Yii::$app->params['addon'] = $addonModel;
        // 菜单
        Yii::$app->params['addonBinding']['menu'] = [];
        // 导航
        Yii::$app->params['addonBinding']['cover'] = [];
        // 模块路由及配置信息
        Yii::$app->params['addonInfo'] = [
            'name' => $addonName,
            'oldRoute' => $route,
        ];

        // 获取关联的菜单和导航
        if (!empty($addonModel->binding))
        {
            $binding = ArrayHelper::toArray($addonModel->binding);
            foreach ($binding as $item)
            {
                Yii::$app->params['addonBinding'][$item['entry']][] = $item;
            }

            unset($binding);
        }

        return true;
    }

    /**
     * 解析路由
     *
     * @param string $route 路由
     * @param string $module 当前模块
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function analysisRoute($route, $module)
    {
        if (!$route)
        {
            throw new NotFoundHttpException("模块路由不能为空");
        }

        $route = explode('/', $route);
        if (($countRoute = count($route)) < 2)
        {
            throw new NotFoundHttpException('路由解析错误,请检查路由地址');
        }

        $oldController = $route[$countRoute - 2];
        $oldAction = $route[$countRoute - 1];

        $controller = StringHelper::strUcwords($oldController);
        $action = StringHelper::strUcwords($oldAction);
        // 删除控制器和方法
        unset($route[$countRoute - 1], $route[$countRoute - 2]);
        $controllerPath = !empty($route) ? implode('\\', $route) : '';
        !empty($controllerPath) && $controllerPath .= '\\';

        $controllerName = $controller . 'Controller';
        $addonRootPath = "\\addons\\" . Yii::$app->params['addonInfo']['name'];
        $tmpInfo = [
            'oldController' => $oldController,
            'oldAction' => $oldAction,
            'controller' => $controller,
            'action' => $action,
            'controllerName' => $controllerName,
            'actionName' => "action" . $action,
            'rootPath' => $addonRootPath,
            'rootAbsolutePath' => Yii::getAlias('@addons') .'/' .Yii::$app->params['addonInfo']['name'],
            'controllersPath' => $addonRootPath . "\\" . $module . "\\controllers\\" . $controllerPath . $controllerName
        ];

        // 存入模块基础的信息
        Yii::$app->params['addonInfo'] = ArrayHelper::merge(Yii::$app->params['addonInfo'], $tmpInfo);
        unset($tmpInfo, $addonRootPath, $controllerName, $controller, $action, $controllerPath);

        return true;
    }
}