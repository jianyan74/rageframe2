<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\helpers\Url;
use common\helpers\AddonHelper;
use common\models\common\Addons;
use common\enums\StatusEnum;
use common\components\Service;
use common\helpers\StringHelper;
use common\helpers\ArrayHelper;
use common\enums\CacheKeyEnum;
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
        $with = ['authChildMenuByBackend'];
        Yii::$app->services->auth->isSuperAdmin() && $with = ['bindingIndexMenu'];

        $models = Addons::find()
            ->select(['title', 'name', 'group'])
            ->with($with)
            ->asArray()
            ->all();

        // 所有菜单信息
        $allAddonsMenu = [];
        if (!Yii::$app->services->auth->isSuperAdmin()) {
            $data = array_column($models, 'authChildMenuByBackend');
            $names = array_column($data, 'addons_name');
            $allAddonsMenu = Yii::$app->services->addonsBinding->regroupMenuByNames($names);
        }

        foreach ($models as $k => &$model) {
            $addon = StringHelper::toUnderScore($model['name']);

            // 超级管理员
            if (Yii::$app->services->auth->isSuperAdmin()) {
                $bindingIndexMenu = $model['bindingIndexMenu'];

                if (isset($bindingIndexMenu['route'])) {
                    $url = ['/addons/' . $addon . '/' . $bindingIndexMenu['route']];
                    $params = isset($bindingIndexMenu['params']) ? Json::decode($bindingIndexMenu['params']) : [];
                    $model['menuUrl'] = Url::to(ArrayHelper::merge($url, $params));
                }

                empty($model['menuUrl']) && $model['menuUrl'] = Url::to(['/addons/blank', 'addon' => StringHelper::toUnderScore($model['name'])]);
                $model['menuUrl'] = urldecode($model['menuUrl']);
            } else {
                $authChildMenuByBackend = $model['authChildMenuByBackend'];

                if (isset($authChildMenuByBackend['name'])) {
                    $url = ['/addons/' . $addon . '/' . $authChildMenuByBackend['name']];
                    // 查询全部的菜单列表进行匹配显示url
                    $key = $authChildMenuByBackend['addons_name'] . '|' . $authChildMenuByBackend['name'];
                    $params = isset($allAddonsMenu[$key]) ? Json::decode($allAddonsMenu[$key]['params']) : [];
                    $model['menuUrl'] = Url::to(ArrayHelper::merge($url, $params));
                }

                if (empty($model['menuUrl'])) {
                    unset($models[$k]);
                } else {
                    $model['menuUrl'] = urldecode($model['menuUrl']);
                }
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
            ->where(['status' => StatusEnum::ENABLED])
            ->select(['id', 'name', 'title'])
            ->andWhere(['in', 'name', $names])
            ->all();
    }

    /**
     * @param $name
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByNameWithBinding($name, $noCache = false)
    {
        $cacheKey = CacheKeyEnum::COMMON_ADDONS . $name;
        if (!($data = Yii::$app->cache->get($cacheKey)) || $noCache == true) {
            $data = Addons::find()
                ->where(['name' => $name, 'status' => StatusEnum::ENABLED])
                ->with(['binding'])
                ->one();

            Yii::$app->cache->set($cacheKey, $data, 7200);
        }

        return $data;
    }

    /**
     * @param $name
     * @param $config
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function update($name, $config)
    {
        if (!($model = $this->findByName($name))) {
            $model = new Addons();
        }

        $model->attributes = $config->info;
        $model->is_setting = $config->isSetting ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->is_hook = $config->isHook ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->is_rule = $config->isRule ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->group = $config->group;
        $model->bootstrap = $config->bootstrap ?? '';
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

        return $model;
    }
}