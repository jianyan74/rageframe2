<?php
namespace backend\widgets\menu;

use Yii;
use yii\base\Widget;
use common\helpers\Auth;
use common\helpers\ArrayHelper;
use common\models\common\Addons;

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
        if (!Yii::$app->services->auth->isSuperAdmin()) {
            $auth = Auth::getAuth();
            $addon['is_rule'] = ($addon['is_rule'] == true && in_array(Addons::AUTH_RULE, $auth));
            $addon['is_cover'] = ($addon['is_cover'] == true && in_array(Addons::AUTH_COVER, $auth));
            $addon['is_setting'] = ($addon['is_setting'] == true && in_array(Addons::AUTH_SETTING, $auth));

            foreach ($menus as $kye => $menu) {
                // 移除无权限菜单
                if (Auth::verify($menu['route'], $auth) === false) {
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