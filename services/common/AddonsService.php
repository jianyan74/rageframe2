<?php
namespace services\common;

use Yii;
use yii\web\NotFoundHttpException;
use common\helpers\Url;
use common\helpers\AddonHelper;
use common\models\common\Addons;
use common\enums\StatusEnum;
use common\components\Service;
use common\helpers\StringHelper;
use common\enums\AuthEnum;
use common\helpers\ArrayHelper;
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
            ->with(['bindingIndexMenu', 'authChildMenuByBackend'])
            ->asArray()
            ->all();

        foreach ($models as $k => &$model) {
            $addon = StringHelper::toUnderScore($model['name']);
            if (Yii::$app->services->auth->isSuperAdmin()) {
                isset($model['bindingIndexMenu']['route']) && $model['menuUrl'] = Url::to(['/addons/'. $addon . '/' . $model['bindingIndexMenu']['route']]);
                empty($model['menuUrl']) && $model['menuUrl'] = Url::to(['/addons/blank', 'addon' => StringHelper::toUnderScore($model['name'])]);
                $model['menuUrl'] = urldecode($model['menuUrl']);
            } else {
                isset($model['authChildMenuByBackend']['name']) && $model['menuUrl'] = Url::to(['/addons/'. $addon . '/' . $model['authChildMenuByBackend']['name']]);
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
     * @return array
     */
    public function getLocalList()
    {
        $addonDir = Yii::getAlias('@addons');

        // 获取插件列表
        $dirs = array_map('basename', glob($addonDir . '/*'));
        $list =	Addons::find()
            ->where(['in', 'name', $dirs])
            ->asArray()
            ->all();

        $tmpAddons = [];
        foreach($list as $addon) {
            $tmpAddons[$addon['name']]	= $addon;
        }

        $addons = [];
        foreach ($dirs as $value) {
            // 判断是否安装
            if (!isset($tmpAddons[$value])) {
                $class = AddonHelper::getAddonConfig($value);

                // 实例化插件失败忽略执行
                if (class_exists($class)) {
                    $config = new $class;
                    $addons[$value]	= $config->info;
                }
            }
        }

        return $addons;
    }

    /**
     * @param $name
     * @param $config
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createAuth($name, $config)
    {
        // 卸载权限
        Yii::$app->services->authItem->uninstallAddonsByName($name);
        $menu = ArrayHelper::getColumn($config->menu, 'route');

        $defaultAuth = [
            [
                'name' => Addons::AUTH_COVER,
                'title' => '应用入口',
                'type' => AuthEnum::TYPE_BACKEND,
                'type_child' => AuthEnum::TYPE_CHILD_ADDONS,
                'addons_name' => $name,
            ],
            [
                'name' => Addons::AUTH_RULE,
                'title' => '规则回复',
                'type' => AuthEnum::TYPE_BACKEND,
                'type_child' => AuthEnum::TYPE_CHILD_ADDONS,
                'addons_name' => $name,
            ],
            [
                'name' => Addons::AUTH_SETTING,
                'title' => '参数设置',
                'type' => AuthEnum::TYPE_BACKEND,
                'type_child' => AuthEnum::TYPE_CHILD_ADDONS,
                'addons_name' => $name,
            ],
        ];

        $authItem = $config->authItem;
        $allAuth = [];
        foreach ($authItem as $key => $item) {
            foreach ($item as $k => $value) {
                $data = [
                    'name' => $k,
                    'title' => $value,
                    'type' => $key,
                    'type_child' => AuthEnum::TYPE_CHILD_ADDONS,
                    'addons_name' => $name,
                ];

                // 判断是否是菜单
                if ($key == AuthEnum::TYPE_BACKEND && in_array($k, $menu)) {
                    $data['is_menu'] = 1;
                }

                $allAuth[] = $data;
                unset($data);
            }
        }

        $installData = ArrayHelper::merge($defaultAuth, $allAuth);

        // 创建权限
        foreach ($installData as $datum) {
            Yii::$app->services->authItem->create($datum);
        }

        unset($data, $allAuth, $installData, $defaultAuth);
    }

    /**
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
    public function findByNameWithBinding($name)
    {
        return Addons::find()
            ->where(['name' => $name, 'status' => StatusEnum::ENABLED])
            ->with(['binding'])
            ->one();
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
        $model->wechat_message = isset($config->wechatMessage) ? serialize($config->wechatMessage) : '';
        $model->updated_at = time();
        // 首先字母转大写拼音
        if (($chinese = StringHelper::strToChineseCharacters($model->title)) && !empty($chinese[0])) {
            $title_initial = mb_substr($chinese[0][0], 0, 1, 'utf-8');
            $model->title_initial = ucwords((new Pinyin())->abbr($title_initial));
        }

        if (!$model->save()) {
            $error = Yii::$app->debris->analyErr($model->getFirstErrors());
            throw new NotFoundHttpException($error);
        }

        return $model;
    }
}