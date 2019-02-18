<?php
namespace wechat\controllers;

use Yii;
use common\components\WechatLoginTrait;
use common\controllers\BaseController;

/**
 * 微信基类
 *
 * Class WController
 * @package wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class WController extends BaseController
{
    use WechatLoginTrait;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!Yii::$app->wechat->isWechat)
        {
            // die('请用微信打开');
        }

        // 修改微信授权方式为静默授权
        // Yii::$app->params['wechatConfig']['oauth']['scopes'] = ['snsapi_base'];

        // 开启微信模拟数据
        Yii::$app->params['simulateUser']['switch'] = true;

        // 微信登录
        $this->login();
    }
}
