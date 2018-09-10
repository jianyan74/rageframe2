<?php
namespace backend\widgets\menu;

use yii\base\Widget;
use common\enums\StatusEnum;
use common\models\sys\Menu;
use common\models\sys\Addons;

/**
 * 菜单
 *
 * Class MainLeftWidget
 * @package backend\widgets\left
 */
class MenuLeftWidget extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        return $this->render('menu-left', [
            'models'=> Menu::getList(StatusEnum::ENABLED),
            'addonsMenu' => Addons::getListMenu(),
        ]);
    }
}