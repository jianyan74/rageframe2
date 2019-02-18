<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;

/**
 * 基类
 *
 * Class BaseController
 * @package common\controllers
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