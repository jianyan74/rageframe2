<?php

namespace services\member;

use Yii;
use common\models\member\Auth;
use common\components\Service;

/**
 * Class AuthService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class AuthService extends Service
{
    /**
     * @param $oauthClient
     * @param $oauthClientUserId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findOauthClient($oauthClient, $oauthClientUserId)
    {
        return Auth::find()
            ->where(['oauth_client' => $oauthClient, 'oauth_client_user_id' => $oauthClientUserId])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $oauthClient
     * @param $oauthClientUserId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findOauthClientWithMember($oauthClient, $oauthClientUserId)
    {
        return Auth::find()
            ->where(['oauth_client' => $oauthClient, 'oauth_client_user_id' => $oauthClientUserId])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with('member')
            ->one();
    }

    /**
     * @param $data
     * @return Auth
     * @throws \Exception
     */
    public function create($data)
    {
        $model = new Auth();
        $model->attributes = $data;
        if (!$model->save()) {
            $error = Yii::$app->debris->analyErr($model->getFirstErrors());
            throw new \Exception($error);
        }

        return $model;
    }
}