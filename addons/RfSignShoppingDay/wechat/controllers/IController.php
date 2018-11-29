<?php
namespace addons\RfSignShoppingDay\wechat\controllers;

use Yii;
use common\controllers\AddonsBaseController;
use addons\RfSignShoppingDay\common\models\User;

/**
 * Class IController
 * @package addons\RfSignShoppingDay\wechat\controllers
 */
class IController extends AddonsBaseController
{
    /**
     * 默认检测到微信进入自动获取用户信息
     *
     * @var bool
     */
    protected $openGetWechatUser = true;

    /**
     * @var string
     */
    public $layout = "@addons/RfSignShoppingDay/wechat/views/layouts/main";

    /**
     * 当前用户
     *
     * @var object
     */
    protected $_user;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        // 开启模拟数据
        Yii::$app->params['simulateUser']['switch'] = true;

        /** 检测到微信进入自动获取用户信息 **/
        if ($this->openGetWechatUser && Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized())
        {
            return Yii::$app->wechat->authorizeRequired()->send();
        }

        /** 当前进入微信用户信息 **/
        Yii::$app->params['wechatMember'] = json_decode(Yii::$app->session->get('wechatUser'), true);

        /** 非微信网页打开时候开启模拟数据 **/
        if (empty(Yii::$app->params['wechatMember']) && Yii::$app->params['simulateUser']['switch'] == true)
        {
            Yii::$app->params['wechatMember'] = Yii::$app->params['simulateUser']['userInfo'];
        }

        // 判断用户信息并插入
        if (isset(Yii::$app->params['wechatMember']['id']))
        {
            $user = User::find()->where(['openid' => Yii::$app->params['wechatMember']['id']])->one();
            if (!$user)
            {
                $user = new User();
                $user = $user->loadDefaultValues();
                $user->openid = Yii::$app->params['wechatMember']['id'];
                $user->nickname = Yii::$app->params['wechatMember']['nickname'];
                $user->avatar = Yii::$app->params['wechatMember']['avatar'];
                $user->ip = Yii::$app->request->userIP;
                $user->save();
            }

            $this->_user = $user;
        }
    }
}