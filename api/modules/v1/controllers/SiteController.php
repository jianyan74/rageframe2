<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\api\AccessToken;
use common\helpers\ResultDataHelper;
use common\models\member\MemberInfo;
use api\controllers\OffAuthController;
use api\modules\v1\models\LoginForm;
use api\modules\v1\models\RefreshForm;

/**
 * 登录接口
 *
 * Class SiteController
 * @package api\modules\v1\controllers
 */
class SiteController extends OffAuthController
{
    public $modelClass = '';

    /**
     * 登录根据用户信息返回accessToken
     *
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionLogin()
    {
        if (Yii::$app->request->isPost)
        {
            $model = new LoginForm();
            $model->attributes = Yii::$app->request->post();
            if ($model->validate())
            {
                return AccessToken::getAccessToken($model->getUser(), $model->group);
            }

            // 返回数据验证失败
            return ResultDataHelper::apiResult(422, $this->analyErr($model->getFirstErrors()));
        }

        throw new NotFoundHttpException('请求出错!');
    }

    /**
     * 重置令牌
     *
     * @param $refresh_token
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionRefresh()
    {
        $model = new RefreshForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate())
        {
            return ResultDataHelper::apiResult(422, $this->analyErr($model->getFirstErrors()));
        }

        $accessToken = AccessToken::find()->where(['refresh_token' => $model->refresh_token])->one();
        if ($accessToken && ($member = MemberInfo::findIdentity($accessToken['member_id'])))
        {
            return AccessToken::getAccessToken($member, $model->group);
        }

        throw new NotFoundHttpException('令牌错误，找不到用户!');
    }
}
