<?php
namespace addons\RfArticle\wechat\controllers;

use Yii;
use common\components\WechatLogin;
use common\controllers\AddonsBaseController;

/**
 * Class WController
 * @package addons\RfArticle\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class WController extends AddonsBaseController
{
    use WechatLogin;

    /**
     * @var string
     */
    public $layout = "@addons/RfArticle/wechat/views/layouts/main";

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        // 修改微信授权方式为静默授权
        // Yii::$app->params['wechatConfig']['oauth']['scopes'] = ['snsapi_base'];

        // 开启微信模拟数据
        Yii::$app->params['simulateUser']['switch'] = true;

        // 微信登录
        $this->login();
    }
}