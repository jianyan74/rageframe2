<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\traits\PayNotify;

/**
 * 支付回调
 *
 * Class NotifyController
 * @package frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyController extends Controller
{
    use PayNotify;

    /**
     * 关闭csrf
     *
     * @var bool
     */
    public $enableCsrfValidation = false;
}