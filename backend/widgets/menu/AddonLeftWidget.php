<?php
namespace backend\widgets\menu;

use Yii;
use yii\base\Widget;

/**
 * 模块菜单
 *
 * Class AddonLeftWidget
 * @package backend\widgets\menu
 */
class AddonLeftWidget extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        return $this->render('addon-left', [
            'addon' => Yii::$app->params['addon'],
            'addonInfo' => Yii::$app->params['addonInfo'],
        ]);
    }
}