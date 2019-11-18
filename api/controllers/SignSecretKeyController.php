<?php

namespace api\controllers;

use Yii;
use common\helpers\EncryptionHelper;
use common\helpers\StringHelper;

/**
 * 签名加密控制器 - Test
 *
 * Class SignSecretKeyController
 * @package api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SignSecretKeyController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 公钥
     *
     * @var string
     */
    protected $appId = 'doormen';

    /**
     * 密钥
     *
     * @var string
     */
    protected $appSecret = 'e3de3825cfbf';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['index', 'create'];

    /**
     * 生成测试带签名秘钥的url
     *
     * 关于创建appId和appSecret可自行生成
     *
     * @return array|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $paraStr = EncryptionHelper::createUrlParam([
            'appId' => $this->appId,
            'time' => time(),
            'nonceStr' => StringHelper::random(32),
            'mobile' => '15888888888',
        ], $this->appSecret);

        return [
            'url' => Yii::$app->request->hostInfo . '/api/sign-secret-key?' . $paraStr,
            'method' => 'post',
            'explain' => '请用post请求该链接进行测试带签名验证'
        ];
    }

    /**
     * 校验签名是否正确
     *
     * @return bool
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        return EncryptionHelper::decodeUrlParam(Yii::$app->request->get(), $this->appSecret);
    }
}