<?php
namespace merchant\widgets\menu;

use Yii;
use yii\base\Widget;

class MenuLeftWidget extends Widget
{
    public function run()
    {
        return $this->render('menu-left', [
            'menus' => Yii::$app->services->menu->getOnAuthList(),
            'addonsMenus' => Yii::$app->services->addons->getMenus(),
        ]);
    }
}