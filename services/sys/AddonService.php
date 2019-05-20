<?php
namespace services\sys;

use Yii;
use yii\helpers\Url;
use common\components\Service;
use common\helpers\StringHelper;
use common\helpers\Auth;
use common\models\sys\Addons;

/**
 * Class Addon
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class AddonService extends Service
{
    /**
     * 获取菜单导航列表
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMenus()
    {
        // 权限判断显示菜单
        $models = $this->getAuthData();

        // 创建分类数组
        $groups = array_keys(Yii::$app->params['addonsGroup']);
        $addons = [];
        foreach ($groups as $group)
        {
            !isset($addons[$group]) && $addons[$group] = [];
        }

        // 模块分类插入
        foreach ($models as $model)
        {
            $addons[$model['group']][] = $model;
        }

        // 删除空模块分类
        foreach ($addons as $key => $vlaue)
        {
            if (empty($vlaue))
            {
                unset($addons[$key]);
            }
        }

        return $addons;
    }

    /**
     * @param $models
     * @return mixed
     */
    protected function getAuthData()
    {
        $models = Addons::find()
            ->select(['title', 'name', 'group'])
            ->with(['bindingMenu'])
            ->asArray()
            ->all();

        // 超级管理员
        if (Yii::$app->services->sys->isAuperAdmin())
        {
            foreach ($models as &$model)
            {
                foreach ($model['bindingMenu'] as $bindingMenu)
                {
                    empty($model['menuUrl']) && $model['menuUrl'] = Url::to(['/addons/execute', 'addon' => StringHelper::toUnderScore($model['name']), 'route' => $bindingMenu['route']]);
                }

                empty($model['menuUrl']) && $model['menuUrl'] = Url::to(['/addons/blank', 'addon' => StringHelper::toUnderScore($model['name'])]);
                $model['menuUrl'] = urldecode($model['menuUrl']);
            }

            return $models;
        }

        // 获取当前所有的插件权限
        $allAuth = Yii::$app->services->sys->addonAuth->getAllAuth();
        foreach ($models as $key => &$model)
        {
            if (in_array($model['name'], $allAuth))
            {
                foreach ($model['bindingMenu'] as $bindingMenu)
                {
                    if (empty($model['menuUrl']) && in_array($model['name'] . ':' . $bindingMenu['route'], $allAuth))
                    {
                        $model['menuUrl'] = Url::to(['/addons/execute', 'addon' => StringHelper::toUnderScore($model['name']), 'route' => $bindingMenu['route']]);
                    }
                }

                empty($model['menuUrl']) && $model['menuUrl'] = Url::to(['/addons/blank', 'addon' => StringHelper::toUnderScore($model['name'])]);
                $model['menuUrl'] = urldecode($model['menuUrl']);
            }
            else
            {
                unset($models[$key]);
            }
        }

        unset($allAuth);
        return $models;
    }
}