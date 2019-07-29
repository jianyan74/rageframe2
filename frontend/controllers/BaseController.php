<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\components\BaseAction;

/**
 * Class BaseController
 * @package frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BaseController extends Controller
{
    use BaseAction;

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