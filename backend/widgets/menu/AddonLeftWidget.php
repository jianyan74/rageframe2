<?php
namespace backend\widgets\menu;

use Yii;
use yii\base\Widget;
use common\helpers\ArrayHelper;
use common\models\sys\AddonsAuthItemChild;

/**
 * 模块菜单
 *
 * Class AddonLeftWidget
 * @package backend\widgets\menu
 * @author jianyan74 <751393839@qq.com>
 */
class AddonLeftWidget extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $addon = ArrayHelper::toArray(Yii::$app->params['addon']);
        $menus = Yii::$app->params['addonBinding']['menu'];

        // 查询我拥有的权限
        $addon['is_cover'] = !empty(Yii::$app->params['addonBinding']['cover']);
        if (!Yii::$app->services->sys->isAuperAdmin())
        {
            $addon['is_setting'] = false;
            $addon['is_rule'] = false;
            $addon['is_cover'] = false;

            $auth = AddonsAuthItemChild::find()
                ->where(['addons_name' => $addon['name']])
                ->asArray()
                ->all();

            $routes = [];
            foreach ($auth as $item)
            {
                $route = explode(':', $item['child']);
                if (count($route) == 2)
                {
                    $route[1] == AddonsAuthItemChild::AUTH_RULE && $addon['is_rule'] = true;
                    $route[1] == AddonsAuthItemChild::AUTH_COVER && $addon['is_cover'] = true;
                    $route[1] == AddonsAuthItemChild::AUTH_SETTING && $addon['is_setting'] = true;
                    $routes[] = $route[1];
                }
            }

            foreach ($menus as $kye => $menu)
            {
                if (!in_array($menu['route'], $routes))
                {
                    unset($menus[$kye]);
                }
            }
        }

        return $this->render('addon-left', [
            'addon' => $addon,
            'menus' => $menus,
        ]);
    }
}