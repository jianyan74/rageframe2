<?php

namespace services\merchant;

use Yii;
use common\enums\StatusEnum;
use common\models\merchant\Auth;
use common\components\Service;

/**
 * Class MemberAuthService
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class MemberAuthService extends Service
{
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

    /**
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByMerchantId($merchant_id)
    {
        return Auth::find()
            ->where(['merchant_id' => $merchant_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->all();
    }

    /**
     * @param $oauthClient
     * @param $memberId
     * @return array|bool|\yii\db\ActiveRecord
     */
    public function unBind($oauthClient, $memberId)
    {
        $model = $this->findOauthClientByMemberId($oauthClient, $memberId);
        if (!$model) {
            return true;
        }

        $model->status = StatusEnum::DISABLED;
        $model->save();

        return $model;
    }

    /**
     * @param $oauthClient
     * @param $memberId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findOauthClientByMemberId($oauthClient, $memberId)
    {
        return Auth::find()
            ->where(['oauth_client' => $oauthClient, 'member_id' => $memberId])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $oauthClient
     * @param $oauthClientUserId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findOauthClient($oauthClient, $oauthClientUserId)
    {
        return Auth::find()
            ->where(['oauth_client' => $oauthClient, 'oauth_client_user_id' => $oauthClientUserId])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }
}