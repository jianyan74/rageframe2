<?php

namespace services\common;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\components\Service;
use common\enums\AuthTypeEnum;
use common\enums\AppEnum;
use common\models\common\Addons;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\common\AuthItem;
use common\models\common\AuthItemChild;

/**
 * Class AuthItemService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AuthItemService extends Service
{
    /**
     * @param array $data
     * @throws UnprocessableEntityHttpException
     */
    public function create(array $data)
    {
        $model = new AuthItem();
        $model->attributes = $data;
        if (!$model->save()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }
    }

    /**
     * @param $allAuthItem
     * @param $allMenu
     * @param $name
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createOnAddons($allAuthItem, $allMenu, $name)
    {
        // 卸载权限
        Yii::$app->services->authItem->uninstallAddonsByName($name);

        $defaultAuth = [
            [
                'name' => Addons::AUTH_COVER,
                'title' => '应用入口',
                'app_id' => AppEnum::BACKEND,
                'type' => AuthTypeEnum::TYPE_ADDONS,
                'addons_name' => $name,
            ],
            [
                'name' => Addons::AUTH_RULE,
                'title' => '规则回复',
                'app_id' => AppEnum::BACKEND,
                'type' => AuthTypeEnum::TYPE_ADDONS,
                'addons_name' => $name,
            ],
            [
                'name' => Addons::AUTH_SETTING,
                'title' => '参数设置',
                'app_id' => AppEnum::BACKEND,
                'type' => AuthTypeEnum::TYPE_ADDONS,
                'addons_name' => $name,
            ],
        ];

        $allAuth = [];
        foreach ($allAuthItem as $key => $item) {
            $menu = isset($allMenu[$key]) ? ArrayHelper::getColumn($allMenu[$key], 'route') : [];
            foreach ($item as $k => $value) {
                $data = [
                    'name' => $k,
                    'title' => $value,
                    'app_id' => $key,
                    'type' => AuthTypeEnum::TYPE_ADDONS,
                    'addons_name' => $name,
                    // 'params' => $name,
                ];

                // 判断是否是菜单
                if ($key == AppEnum::BACKEND && in_array($k, $menu)) {
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
     * 卸载插件
     *
     * @param $name
     */
    public function uninstallAddonsByName($name)
    {
        AuthItem::deleteAll(['type' => AuthTypeEnum::TYPE_ADDONS, 'addons_name' => $name]);
        AuthItemChild::deleteAll(['type' => AuthTypeEnum::TYPE_ADDONS, 'addons_name' => $name]);
    }

    /**
     * @param array $ids
     * @param string $app_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList($app_id = AppEnum::BACKEND, $ids = [])
    {
        return AuthItem::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->andFilterWhere(['in', 'id', $ids])
            ->select(['id', 'title', 'name', 'pid', 'level', 'app_id', 'type', 'addons_name', 'is_menu'])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();
    }

    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getEditDropDownList($id = '')
    {
        $list = AuthItem::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['app_id' => AppEnum::BACKEND, 'type' => AuthTypeEnum::TYPE_DEFAULT])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
        return ArrayHelper::merge([0 => '顶级权限'], $data);
    }
}