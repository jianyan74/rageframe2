<?php
namespace wechat\controllers;

use Yii;
use common\controllers\BaseController;

/**
 * 微信基类
 *
 * Class WController
 * @package wechat\controllers
 */
class WController extends BaseController
{
    /**
     * 默认检测到微信进入自动获取用户信息
     *
     * @var bool
     */
    protected $openGetWechatUser = true;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

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
    }
}
