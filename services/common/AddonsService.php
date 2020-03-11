<?php

namespace services\common;

use common\enums\AppEnum;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\helpers\Url;
use common\helpers\AddonHelper;
use common\models\common\Addons;
use common\enums\StatusEnum;
use common\components\Service;
use common\helpers\StringHelper;
use common\helpers\ArrayHelper;
use common\enums\CacheEnum;
use common\components\BaseAddonConfig;
use common\helpers\Auth;
use Overtrue\Pinyin\Pinyin;

/**
 * Class AddonsService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsService extends Service
{
    /**
     * @return array
     */
    public function getMenus()
    {
        $models = Addons::find()
            ->select(['title', 'name', 'group'])
            ->with('bindingMenu')
            ->asArray()
            ->all();

        $isSuperAdmin = Yii::$app->services->auth->isSuperAdmin();
        foreach ($models as $k => &$model) {
            $model['menuUrl'] = '';

            foreach ($model['bindingMenu'] as $bindingMenu) {
                if (isset($bindingMenu['route']) && empty($model['menuUrl'])) {
                    $params = isset($bindingMenu['params']) ? Json::decode($bindingMenu['params']) : [];
                    $model['menuUrl'] = Url::to(ArrayHelper::merge([$bindingMenu['route']], $params));

                    if (!$isSuperAdmin && !Auth::verify($bindingMenu['route'])) {
                        $model['menuUrl'] = '';
                    }
                }
            }

            if (empty($model['menuUrl'])) {
                unset($models[$k]);
            } else {
                $model['menuUrl'] = urldecode($model['menuUrl']);
            }
        }

        // 创建分类数组
        $groups = array_keys(Yii::$app->params['addonsGroup']);
        $addons = [];
        foreach ($groups as $group) {
            !isset($addons[$group]) && $addons[$group] = [];
        }

        // 模块分类插入
        foreach ($models as $record) {
            $addons[$record['group']][] = $record;
        }

        // 删除空模块分类
        foreach ($addons as $key => $vlaue) {
            if (empty($vlaue)) {
                unset($addons[$key]);
            }
        }

        return $addons;
    }

    /**
     * 获取配置文件
     *
     * @param $name
     * @return bool|string
     */
    public function getConfigClass($name)
    {
        $class = AddonHelper::getAddonConfig($name);
        if (!class_exists($class)) {
            return false;
        }

        return $class;
    }

    /**
     * 获取本地插件列表
     *
     * @return array
     */
    public function getLocalList()
    {
        $addonDir = Yii::getAlias('@addons');

        // 获取插件列表
        $dirs = array_map('basename', glob($addonDir . '/*'));
        $list = Addons::find()
            ->where(['in', 'name', $dirs])
            ->asArray()
            ->all();

        $tmpAddons = [];
        foreach ($list as $addon) {
            $tmpAddons[$addon['name']] = $addon;
        }

        $addons = [];
        foreach ($dirs as $value) {
            // 判断是否安装
            if (!isset($tmpAddons[$value])) {
                $class = AddonHelper::getAddonConfig($value);

                // 实例化插件失败忽略执行
                if (class_exists($class)) {
                    $config = new $class;
                    $addons[$value] = $config->info;
                }
            }
        }

        return $addons;
    }

    /**
     * 更新配置
     *
     * @param $name
     * @param BaseAddonConfig $config
     * @param $default_config
     * @return array|Addons|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public function update($name, $config, $default_config)
    {
        if (!($model = $this->findByName($name))) {
            $model = new Addons();
        }

        $model->attributes = $config->info;
        $model->is_setting = $config->isSetting ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->is_rule = $config->isRule ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->is_merchant_route_map = $config->isMerchantRouteMap ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->group = $config->group;
        $model->bootstrap = $config->bootstrap ?? '';
        $model->service = $config->service ?? '';
        $model->default_config = $default_config;
        $model->console = $config->console ?? [];
        $model->wechat_message = $config->wechatMessage;
        $model->updated_at = time();
        // 首先字母转大写拼音
        if (($chinese = StringHelper::strToChineseCharacters($model->title)) && !empty($chinese[0])) {
            $title_initial = mb_substr($chinese[0][0], 0, 1, 'utf-8');
            $model->title_initial = ucwords((new Pinyin())->abbr($title_initial));
        }

        if (!$model->save()) {
            throw new NotFoundHttpException($this->getError($model));
        }

        // 更新缓存
        Yii::$app->services->addons->updateCacheByName($name);

        return $model;
    }

    /**
     * 获取列表
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return Addons::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * @param $name
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByName($name)
    {
        return Addons::find()
            ->where(['name' => $name, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param array $names
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByNames(array $names)
    {
        return Addons::find()
            ->select(['id', 'name', 'title'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['in', 'name', $names])
            ->all();
    }

    /**
     * @param $name
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByNameWithBinding($name, $noCache = false)
    {
        $cacheKey = 'addonsConfig'. ':' . Yii::$app->id . ':' . $name;
        if (!$noCache && Yii::$app->cache->exists($cacheKey)) {
            return Yii::$app->cache->get($cacheKey);
        }

        $data = Addons::find()
            ->where(['name' => $name, 'status' => StatusEnum::ENABLED])
            ->with([
                'bindingMenu' => function (ActiveQuery $query) {
                    return $query->andWhere(['app_id' => Yii::$app->id]);
                },
                'bindingCover'
            ])
            ->one();

        Yii::$app->cache->set($cacheKey, $data, 7200);

        return $data;
    }

    /**
     * 获取插件名称列表
     *
     * @param bool $noCache
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public function findAllNames($noCache = false)
    {
        $cacheKey = CacheEnum::getPrefix('addonsName');
        if (!$noCache && Yii::$app->cache->exists($cacheKey)) {
            return Yii::$app->cache->get($cacheKey);
        }

        $models = Addons::find()
            ->select(['name', 'is_merchant_route_map', 'service', 'updated_at'])
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        Yii::$app->cache->set($cacheKey, $models, 7200);

        return $models;
    }

    /**
     * 触发更新缓存
     *
     * @param $name
     */
    public function updateCacheByName($name)
    {
        $this->findAllNames(true);
        $this->findByNameWithBinding($name, true);
    }
}