<?php

namespace merapi\modules\v1\controllers;

use Yii;
use merapi\controllers\OnAuthController;
use merapi\modules\v1\forms\MiniProgramLoginForm;
use common\models\merchant\Member;
use common\enums\AccessTokenGroupEnum;
use common\helpers\ResultHelper;
use common\models\member\Auth;

/**
 * 小程序授权验证
 *
 * Class MiniProgramController
 * @package merapi\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MiniProgramController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['login'];

    /**
     * 登录认证
     *
     * @return array|mixed
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionLogin()
    {
        $model = new MiniProgramLoginForm();
        $model->attributes = Yii::$app->request->post();

        if (!$model->validate()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        $userinfo = $model->getUser();

        // 插入到用户授权表
        if (!($memberAuthInfo = Yii::$app->services->memberAuth->findOauthClient(Auth::CLIENT_WECHAT_MP, $userinfo['openId']))) {
            $memberAuthInfo = Yii::$app->services->memberAuth->create([
                'unionid' => $userinfo['unionId'] ?? '',
                'oauth_client' => Auth::CLIENT_WECHAT_MP,
                'oauth_client_user_id' => $userinfo['openId'],
                'gender' => $userinfo['gender'],
                'nickname' => $userinfo['nickName'],
                'head_portrait' => $userinfo['avatarUrl'],
                'country' => $userinfo['country'],
                'province' => $userinfo['province'],
                'city' => $userinfo['city'],
                'language' => $userinfo['language'],
            ]);
        }

        // TODO 查询自己关联的用户信息并处理自己的登录请求，并返回用户数据
        // TODO 以下代码都可以替换

        // 判断是否有管理信息 数据也可以后续在绑定
        if (!($member = $memberAuthInfo->member)) {
            $member = new Member();
            $member->attributes = [
                'gender' => $userinfo['gender'],
                'nickname' => $userinfo['nickName'],
                'head_portrait' => $userinfo['avatarUrl'],
            ];
            $member->save();

            // 关联用户
            $memberAuthInfo->member_id = $member['id'];
            $memberAuthInfo->save();
        }

        return Yii::$app->services->merapiAccessToken->getAccessToken($member, AccessTokenGroupEnum::WECHAT_MQ);
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