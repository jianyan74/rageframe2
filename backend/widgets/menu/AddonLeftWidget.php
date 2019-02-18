<?php
namespace backend\widgets\menu;

use common\helpers\AddonAuthHelper;
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

            $auth = AddonAuthHelper::getAuth();
            in_array(AddonsAuthItemChild::AUTH_RULE, $auth) && $addon['is_rule'] = true;
            in_array(AddonsAuthItemChild::AUTH_COVER, $auth) && $addon['is_cover'] = true;
            in_array(AddonsAuthItemChild::AUTH_SETTING, $auth) && $addon['is_setting'] = true;

            foreach ($menus as $kye => $menu)
            {
                if (!in_array($menu['route'], $auth))
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