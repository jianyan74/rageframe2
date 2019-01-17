<?php
namespace common\services\sys;

use Yii;
use yii\helpers\Url;
use common\services\Service;
use common\helpers\StringHelper;

/**
 * Class Addon
 * @package common\services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class Addon extends Service
{
    /**
     * 获取菜单导航列表
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getInfo()
    {
        // 权限判断显示菜单
        $models = $this->authFilter(Yii::$app->services->sys->auth->getAddonAuth());
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
    protected function authFilter($models)
    {
        $menus = [];
        $isAuperAdmin = Yii::$app->services->sys->isAuperAdmin();
        foreach ($models as $model)
        {
            $menu = [];
            $menu['title'] = $model['title'];
            $menu['name'] = $model['name'];
            $menu['group'] = $model['group'];
            $menu['menu'] = [];

            // 获取自己拥有的权限
            $authItem = [];
            foreach ($model['authItem'] as $item)
            {
                $authItem[] = $item['route'];
            }

            if(!empty($model['bindingMenu']))
            {
                foreach ($model['bindingMenu'] as $bindingMenu)
                {
                    if (in_array($bindingMenu['route'], $authItem) || $isAuperAdmin)
                    {
                        $menu['menu'][] = $bindingMenu;
                       empty($menu['menuUrl']) && $menu['menuUrl'] = Url::to(['/addons/execute', 'addon' => StringHelper::toUnderScore($model['name']), 'route' => $bindingMenu['route']]);
                    }
                }
            }

            empty($menu['menuUrl']) && $menu['menuUrl'] = Url::to(['/addons/blank', 'addon' => StringHelper::toUnderScore($model['name'])]);
            $menu['menuUrl'] = urldecode($menu['menuUrl']);
            $menus[] = $menu;

            unset($menu, $authItem);
        }

        unset($models);
        return $menus;
    }
}