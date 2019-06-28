<?php
namespace addons\RfArticle\wechat\controllers;

use Yii;
use common\controllers\AddonsController;
use common\components\WechatLogin;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfArticle\wechat\controllers
 */
class BaseController extends AddonsController
{
    use WechatLogin;

    /**
    * @var string
    */
    public $layout = "@addons/RfArticle/wechat/views/layouts/main";
}