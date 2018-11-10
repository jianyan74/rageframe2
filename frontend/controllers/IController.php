<?php
namespace frontend\controllers;

use Yii;
use common\controllers\BaseController;

/**
 * Class IController
 * @package frontend\controllers
 */
class IController extends BaseController
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        // Yii::$app->language = 'en';  //指定使用哪个语言翻译

        parent::init();
    }
}