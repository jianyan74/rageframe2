<?php
namespace addons\RfSignShoppingDay\wechat\controllers;

use Yii;
use addons\RfSignShoppingDay\common\models\User;
use common\components\WechatLogin;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfSignShoppingDay\wechat\controllers
 */
class BaseController extends AddonsController
{
    use WechatLogin;

    /**
    * @var string
    */
    public $layout = "@addons/RfSignShoppingDay/wechat/views/layouts/main";

    public $user;

    public function init()
    {
        parent::init();

        // 微信登录
        Yii::$app->params['simulateUser']['switch'] = true;
        $this->login();

        if (!($this->user = User::find()->where(['openid' => $this->openid])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one())) {
            $user = new User();
            $user = $user->loadDefaultValues();
            $user->openid = Yii::$app->params['wechatMember']['id'];
            $user->nickname = Yii::$app->params['wechatMember']['nickname'];
            $user->avatar = Yii::$app->params['wechatMember']['avatar'];
            $user->ip = Yii::$app->request->userIP;
            $user->save();

            $this->user = $user;
        }
    }
}