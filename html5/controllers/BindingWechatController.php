<?php

namespace html5\controllers;

use Yii;
use yii\helpers\Json;
use common\enums\MemberAuthEnum;
use common\helpers\HashidsHelper;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class BindingWechatController
 * @package html5\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BindingWechatController extends BaseController
{
    /**
     * @param \yii\base\Action $action
     * @return bool|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function beforeAction($action)
    {
        /** 检测到微信进入自动获取用户信息 **/
        if (Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
            return Yii::$app->wechat->authorizeRequired()->send();
        }

        /** 当前进入微信用户信息 **/
        Yii::$app->params['wechatMember'] = Json::decode(Yii::$app->session->get('wechatUser'));

        /** 非微信网页打开时候开启模拟数据 **/
        if (empty(Yii::$app->params['wechatMember']) && Yii::$app->params['simulateUser']['switch'] == true) {
            Yii::$app->params['wechatMember'] = Yii::$app->params['simulateUser']['userInfo'];
        }

        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function actionIndex($uuid)
    {
        $member_id = HashidsHelper::decode($uuid);
        if (!Yii::$app->wechat->isWechat) {
            throw new UnprocessableEntityHttpException('请用微信打开');
        }

        if (empty($member = Yii::$app->services->backendMember->findById($member_id))) {
            throw new UnprocessableEntityHttpException('找不到用户信息');
        }

        $original = Yii::$app->params['wechatMember']['original'];
        $auth = Yii::$app->services->backendMemberAuth->findOauthClientByMemberId(MemberAuthEnum::WECHAT, $member_id);
        $message = '请先解绑再绑定';
        if (empty($auth)) {
            Yii::$app->services->backendMemberAuth->create([
                'member_id' => $member_id,
                'oauth_client' => MemberAuthEnum::WECHAT,
                'oauth_client_user_id' => $original['openid'],
                'gender' => $original['sex'],
                'nickname' => $original['nickname'],
                'head_portrait' => $original['headimgurl'],
                'country' => $original['country'],
                'province' => $original['province'],
                'city' => $original['city'],
                'language' => $original['language'],
            ]);

            $message = '绑定成功';
        }

        return $this->render($this->action->id, [
            'message' => $message,
        ]);
    }
}