<?php

namespace addons\Merchants\merapi\modules\v1\controllers\member;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\models\merchant\Auth;
use merapi\controllers\UserAuthController;

/**
 * Class AuthController
 * @package addons\Merchants\api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class AuthController extends UserAuthController
{
    /**
     * @var Auth
     */
    public $modelClass = Auth::class;

    /**
     * 绑定第三方信息
     *
     * @return array|mixed|\yii\db\ActiveRecord|null
     */
    public function actionCreate()
    {
        $oauthClient = Yii::$app->request->post('oauth_client');
        $oauthClientUserId = Yii::$app->request->post('oauth_client_user_id');

        /** @var Auth $model */
        if (!($model = Yii::$app->services->merchantMemberAuth->findOauthClient($oauthClient, $oauthClientUserId))) {
            $model = new $this->modelClass();
            $model = $model->loadDefaultValues();
            $model->attributes = Yii::$app->request->post();
        }

        if (!$model->isNewRecord && $model->status == StatusEnum::ENABLED) {
            return ResultHelper::json(422, '请先解除该账号绑定');
        }

        $model->oauth_client = $oauthClient;
        $model->oauth_client_user_id = $oauthClientUserId;
        $model->member_id = Yii::$app->user->identity->member_id;
        $model->status = StatusEnum::ENABLED;
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        // 更改用户信息
        if ($member = Yii::$app->services->merchantMember->get($model->member_id)) {
            !$member->head_portrait && $member->head_portrait = $model->head_portrait;
            !$member->gender && $member->gender = $model->gender;
            !$member->nickname && $member->nickname = $model->nickname;
            $member->save();
        }

        return $model;
    }

    /**
     * @return array|mixed
     */
    public function actionIsBinding()
    {
        $oauthClient = Yii::$app->request->post('oauth_client');

        $model = Yii::$app->services->merchantMemberAuth->findOauthClientByMemberId($oauthClient, Yii::$app->user->identity->member_id);
        if ($model) {
            return [
                'openid' => $model['oauth_client_user_id']
            ];
        }

        return ResultHelper::json(422, '请先授权绑定用户');
    }
}