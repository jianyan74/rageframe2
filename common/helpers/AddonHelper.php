<?php

namespace common\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\enums\CacheEnum;
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
     * 服务
     *
     * @var array
     */
    protected static $_service = [];

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

        return Yii::getAlias('@web') . '/resources/img/icon.jpg';
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
            $assets[] = Yii::$app->params['addon']['name'];
            $assets[] = Yii::$app->params['real_app_id'];
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
     * @param $path
     * @param array $options
     * @param string $assets
     * @return string
     */
    public static function jsFile($path, $options = [], $assets = '')
    {
        return Html::jsFile(self::filePath($assets) . $path, $options);
    }

    /**
     * @param $path
     * @param array $options
     * @param string $assets
     * @return string
     */
    public static function cssFile($path, $options = [], $assets = '')
    {
        return Html::cssFile(self::filePath($assets) . $path, $options);
    }

    /**
     * @param $key
     * @param bool $noCache
     * @param string $merchant_id
     * @return mixed|string
     */
    public static function getConfigByKey($key, $noCache = false, $merchant_id = '')
    {
        $config = static::getConfig($noCache, $merchant_id);

        return $config[$key] ?? '';
    }

    /**
     * 获取配置信息
     *
     * @return array|mixed
     */
    public static function getConfig($noCache = false, $merchant_id = '')
    {
        $name = Yii::$app->params['addon']['name'];

        return static::findConfig($noCache, $merchant_id, $name);
    }

    /**
     * 写入配置信息
     *
     * @param $config
     * @return array|mixed
     */
    public static function setConfig(array $config)
    {
        $merchant_id = Yii::$app->services->merchant->getId();
        $name = Yii::$app->params['addon']['name'];
        if (empty($configModel = Yii::$app->services->addonsConfig->findByName($name))) {
            $configModel = new AddonsConfig();
            $configModel->addons_name = $name;
            $configModel->data = [];
        }

        $configModel->data = ArrayHelper::merge($configModel->data, $config);
        $configModel->save();

        return self::getConfig(true, $merchant_id);
    }

    /**
     * 获取配置信息
     *
     * @return array|mixed
     */
    public static function findConfig($noCache, $merchant_id, $name)
    {
        if (!$merchant_id) {
            $merchant_id = Yii::$app->services->merchant->getId();
        }

        $cacheKey = CacheEnum::getPrefix('addonsConfig', $name . ':' . $merchant_id);
        if ($noCache == true || !($configModel = Yii::$app->cache->get($cacheKey))) {
            if (empty($configModel = Yii::$app->services->addonsConfig->findByName($name, $merchant_id))) {
                return [];
            }

            Yii::$app->cache->set($cacheKey, $configModel, 7200);
        }

        return $configModel->data;
    }

    /**
     * 调用其他插件的服务
     *
     * @param $name
     * @return object
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function service($name)
    {
        if (!$name) {
            throw new NotFoundHttpException("插件名称不能为空");
        }

        if (!($addon = Yii::$app->services->addons->findByNameWithBinding($name))) {
            throw new NotFoundHttpException($name . "插件不存在，请先安装");
        }

        // 初始化服务
        if (empty($addon->service)) {
            throw new NotFoundHttpException($name . "不支持服务调用");
        }

        // 动态注入服务
        $service_name = lcfirst($addon->name) . 'Service';
        if (isset(self::$_service[$service_name])) {
           return self::$_service[$service_name];
        }

        Yii::$app->set($service_name, [
            'class' => $addon->service,
        ]);

        self::$_service[$service_name] = Yii::$app->get($service_name);

        return self::$_service[$service_name];
    }

    /**
     * 初始化模块信息
     *
     * @param string $name 模块名称
     * @param string $route 路由
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function initAddon($name)
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
        Yii::$app->params['addonBinding']['menu'] = !empty($addon['bindingMenu']) ? ArrayHelper::toArray($addon['bindingMenu']) : [];
        // 导航
        Yii::$app->params['addonBinding']['cover'] = !empty($addon['bindingCover']) ? ArrayHelper::toArray($addon['bindingCover']) : [];

        Yii::$app->params['addonName'] = StringHelper::toUnderScore(Yii::$app->params['addon']['name']);
        Yii::$app->params['inAddon'] = true;

        return true;
    }
}