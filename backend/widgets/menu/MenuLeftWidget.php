<?php

namespace backend\widgets\menu;

use Yii;
use yii\base\Widget;

/**
 * 左边菜单
 *
 * Class MenuLeftWidget
 * @package backend\widgets\menu
 * @author jianyan74 <751393839@qq.com>
 */
class MenuLeftWidget extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        return $this->render('menu-left', [
            'menus' => Yii::$app->services->sysMenu->getList(),
            'addonsMenus' => Yii::$app->services->addons->getMenus(),
        ]);
    }
}