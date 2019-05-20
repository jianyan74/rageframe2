<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Class BaseController
 * @package frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BaseController extends Controller
{
    /**
     * 默认分页
     *
     * @var int
     */
    protected $pageSize = 10;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        // 指定使用哪个语言翻译
        // Yii::$app->language = 'en';

        return parent::init();
    }

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    protected function analyErr($firstErrors)
    {
        return Yii::$app->debris->analyErr($firstErrors);
    }
}