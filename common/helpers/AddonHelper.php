<?php

namespace common\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\enums\CacheKeyEnum;
use common\models\common\AddonsConfig;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

/**
 * Class AddonHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonHelper
{
    /**
     * 获取插件配置
     *
     * @param $name
     * @return string
     */
    public static function getAddonConfig($name)
    {
        return static::getAddonRoot($name) . "AddonConfig";
    }

    /**
     * 获取插件微信消息配置
     *
     * @param $name
     * @return string
     */
    public static function getAddonMessage($name)
    {
        return static::getAddonRoot($name) . "AddonMessage";
    }

    /**
     * 获取插件的命名空间
     *
     * @param $name
     * @return string
     */
    public static function getAddonRoot($name)
    {
        return "addons" . "\\" . $name . "\\";
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
     * 验证插件目录是否存在
     *
     * @param $name
     * @return bool
     */
    public static function has($name)
    {
        if (!is_dir(static::getAddonRootPath($name))) {
            return false;
        }

        return true;
    }

    /**
     * @param $name
     * @return string
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function getAddonIcon($name)
    {
        $adapter = new Local(Yii::getAlias('@root'));
        $filesystem = new Filesystem($adapter);

        $localIconPath = static::getAddonRoot($name) . 'icon.jpg';
        if ($filesystem->has($localIconPath)) {
            $md5 = md5(Json::encode($filesystem->getMetadata($localIconPath)));
            $newPath = '/assets/tmp/' . $md5 . '.jpg';
            $newLocalIconPath = 'web/backend' . $newPath;

            if (!$filesystem->has($newLocalIconPath)) {
                $filesystem->copy($localIconPath, $newLocalIconPath);
            }

            return '/backend' . $newPath;
        }

        return Yii::getAlias('@web') . '/resources/dist/img/icon.jpg';
    }

    /**
     * 获取生成asset的资源文件目录
     *
     * @param string $assets
     * @return string
     */
    public static function filePath($assets = '')
    {
        if (!$assets) {
            $assets = [];
            $assets[] = 'addons';
            $assets[] = Yii::$app->params['addonInfo']['name'];
            $assets[] = Yii::$app->id;
            $assets[] = 'assets';
            $assets[] = 'AppAsset';
            $assets = implode('\\', $assets);
        }

        if (!isset(Yii::$app->view->assetBundles[$assets])) {
            /* @var $assets \yii\web\AssetBundle */
            $assets::register(Yii::$app->view);
        }

        return Yii::$app->view->assetBundles[$assets]->baseUrl . '/';
    }

    /**
     * 获取资源文件
     *
     * @return string
     */
    public static function file($path, $assets = '')
    {
        return self::filePath($assets) . $path;
    }

    /**
     * 获取配置信息
     *
     * @return array|mixed
     */
    public static function getConfig($noCache = false)
    {
        $name = Yii::$app->params['addon']['name'];
        $cacheKey = CacheKeyEnum::COMMON_ADDONS_CONFIG . $name;
        if (!($configModel = Yii::$app->cache->get($cacheKey)) || $noCache == true) {
            if (empty($configModel = Yii::$app->services->addonsConfig->findByName($name))) {
                return [];
            }

            Yii::$app->cache->set($cacheKey, $configModel, 7200);
        }

        return $configModel->data;
    }

    /**
     * 写入配置信息
     *
     * @param $config
     * @return array|mixed
     */
    public static function setConfig(array $config)
    {
        $name = Yii::$app->params['addon']['name'];
        if (empty($configModel = Yii::$app->services->addonsConfig->findByName($name))) {
            $configModel = new AddonsConfig();
            $configModel->addons_name = $name;
            $configModel->data = [];
        }

        $configModel->data = ArrayHelper::merge($configModel->data, $config);
        $configModel->save();

        return self::getConfig(true);
    }

    /**
     * 初始化模块信息
     *
     * @param string $name 模块名称
     * @param string $route 路由
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function initAddon($name, $route)
    {
        if (!$name) {
            throw new NotFoundHttpException("插件不能为空");
        }

        if (!($addon = Yii::$app->services->addons->findByNameWithBinding($name))) {
            throw new NotFoundHttpException("插件不存在");
        }

        // 当前模块实例
        Yii::$app->params['addon'] = $addon;
        // 菜单
        Yii::$app->params['addonBinding']['menu'] = [];
        // 导航
        Yii::$app->params['addonBinding']['cover'] = [];
        // 模块路由及配置信息
        Yii::$app->params['addonInfo'] = [
            'name' => $name,
            'oldRoute' => $route,
        ];
        Yii::$app->params['inAddon'] = true;

        // 获取关联的菜单和导航
        if (!empty($addon->binding)) {
            $binding = ArrayHelper::toArray($addon->binding);

            foreach ($binding as $item) {
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
        if (!$route) {
            throw new NotFoundHttpException("插件路由不能为空");
        }

        $route = explode('/', $route);
        if (($countRoute = count($route)) < 2) {
            throw new NotFoundHttpException('路由解析错误, 请检查路由地址');
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
        $addonRootPath = "\\" . "addons" . "\\" . Yii::$app->params['addonInfo']['name'];
        $tmpInfo = [
            'oldController' => $oldController,
            'oldAction' => $oldAction,
            'controller' => $controller,
            'action' => $action,
            'controllerName' => $controllerName,
            'actionName' => "action" . $action,
            'rootPath' => $addonRootPath,
            'rootAbsolutePath' => Yii::getAlias('@addons') . '/' . Yii::$app->params['addonInfo']['name'],
            'controllersPath' => $addonRootPath . "\\" . $module . '\\' . "controllers" . '\\' . $controllerPath . $controllerName
        ];

        if (!class_exists($tmpInfo['controllersPath'])) {
            throw new NotFoundHttpException('页面未找到。');
        }

        // 存入模块基础的信息
        Yii::$app->params['addonInfo'] = ArrayHelper::merge(Yii::$app->params['addonInfo'], $tmpInfo);
        unset($tmpInfo, $addonRootPath, $controllerName, $controller, $action, $controllerPath);

        return true;
    }
}