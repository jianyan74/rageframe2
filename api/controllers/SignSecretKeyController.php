<?php
namespace api\controllers;

use Yii;
use common\helpers\EncryptionHelper;
use common\helpers\StringHelper;

/**
 * 签名加密测试控制器
 *
 * Class SignSecretKeyController
 * @package api\controllers
 */
class SignSecretKeyController extends OffAuthController
{
    public $modelClass = '';

    /**
     * 生成测试带签名秘钥的url
     *
     * appId: doormen
     * appSecret: e3de3825cfbf
     *
     * 关于创建appId和appSecret可自行设置
     *
     * @return array|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $paraStr = EncryptionHelper::createUrlParam([
            'appId' => 'doormen',
            'time' => time(),
            'nonceStr' => StringHelper::random(32),
            'mobile' => '15888888888',
        ], 'e3de3825cfbf');

        return [
            'url' => Yii::$app->request->hostInfo . '/api/sign-secret-key?' . $paraStr,
            'method' => 'post'
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
        return EncryptionHelper::decodeUrlParam(Yii::$app->request->get(), 'e3de3825cfbf');
    }
}