<?php

namespace addons\Merchants\merapi\modules\v1\controllers;

use Yii;
use yii\helpers\Json;
use api\controllers\OnAuthController;
use api\modules\v1\forms\MiniProgramLoginForm;
use common\helpers\ResultHelper;
use common\models\merchant\Auth;
use addons\TinyShop\common\enums\AccessTokenGroupEnum;

/**
 * 第三方授权登录
 *
 * Class ThirdPartyController
 * @package addons\Merchants\api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ThirdPartyController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['wechat', 'wechat-mp', 'wechat-js-sdk'];

    /**
     * 微信登录
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionWechat()
    {
        if (!Yii::$app->request->get('code')) {
            return ResultHelper::json(422, '请传递 code');
        }

        $user = Yii::$app->wechat->app->oauth->user();
        $auth = Yii::$app->services->memberAuth->findOauthClient(Auth::CLIENT_WECHAT, $user['id']);
        if ($auth && $auth->member) {
            return [
                'login' => true,
                'user_info' => $this->getData($auth),
            ];
        }

        return [
            'login' => false,
            'user_info' => $user
        ];
    }

    /**
     * 微信小程序登录
     *
     * @return array|mixed
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionWechatMp()
    {
        $model = new MiniProgramLoginForm();
        $model->attributes = Yii::$app->request->post();

        if (!$model->validate()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        $user = $model->getUser();
        $auth = Yii::$app->services->memberAuth->findOauthClient(Auth::CLIENT_WECHAT_MP, $user['openId']);
        if ($auth && $auth->member) {
            $user_info = $this->getData($auth);
            unset($user_info['watermark']);

            return [
                'login' => true,
                'user_info' => $user_info,
            ];
        }

        return [
            'login' => false,
            'user_info' => $user
        ];
    }

    /**
     * 生成小程序码
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function actionQrCode()
    {
        // $response = $app->app_code->get('path/to/page');
        // 指定颜色
        $response = Yii::$app->wechat->miniProgram->app_code->get('path/to/page', [
            'width' => 600,
            'line_color' => [
                'r' => 105,
                'g' => 166,
                'b' => 134,
            ],
        ]);

        // $response 成功时为 EasyWeChat\Kernel\Http\StreamResponse 实例，失败时为数组或者你指定的 API 返回格式

        $directory = Yii::getAlias('@attachment');

        // 保存小程序码到文件
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->save($directory);
        }

        // 或
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->saveAs($directory, 'appcode.png');
        }
    }

    /**
     * 微信jssdk
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionWechatJsSdk()
    {
        $url = Yii::$app->request->post('url');
        $apis = Yii::$app->request->post('jsApiList');
        $debug = Yii::$app->request->post('debug', false);

        $apis = !empty($apis) ? Json::decode($apis) : [];

        $app = Yii::$app->wechat->app;
        $app->jssdk->setUrl($url);

        return $app->jssdk->buildConfig($apis, $debug, $beta = false, $json = false);
    }

    /**
     * @param $auth
     * @return array
     * @throws \yii\base\Exception
     */
    protected function getData($auth)
    {
        $data = Yii::$app->services->apiAccessToken->getAccessToken($auth->member, AccessTokenGroupEnum::WECHAT_MQ);
        // 优惠券数量
        $data['member']['coupon_num'] = Yii::$app->MerchantsService->marketingCoupon->findCountByMemberId($data['member']['id']);
        // 订单数量统计
        $data['member']['order_synthesize_num'] = Yii::$app->MerchantsService->order->getOrderCountGroupByMemberId($data['member']['id']);

        return $data;
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['index', 'view', 'update', 'create', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}