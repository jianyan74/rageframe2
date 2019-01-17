<?php
namespace frontend\controllers;

use Yii;
use common\controllers\BaseController;

/**
 * Class IController
 * @package frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class IController extends BaseController
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        // 指定使用哪个语言翻译
        // Yii::$app->language = 'en';

        return parent::init();
    }
}