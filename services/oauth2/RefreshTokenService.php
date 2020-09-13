<?php

namespace services\oauth2;

use common\components\Service;
use common\models\oauth2\RefreshToken;

/**
 * Class RefreshTokenService
 * @package services\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class RefreshTokenService extends Service
{
    /**
     * @param $client_id
     * @param $grant_type
     * @param $refresh_token
     * @param $expires
     * @param $member_id
     * @param $scopes
     */
    public function create($client_id, $grant_type, $refresh_token, $expires, $member_id, $scopes)
    {
        if (!($model = $this->findByClientId($client_id, $grant_type, $member_id))) {
            $model = new RefreshToken();
            $model->client_id = $client_id;
            $model->grant_type = $grant_type;
            $model->member_id = (string)$member_id;
        }

        $model->expires = $expires;
        $model->refresh_token = $refresh_token;
        $model->scope = $scopes;
        $model->save();
    }

    /**
     * @param $tokenId
     */
    public function deleteByRefreshToken($tokenId)
    {
        RefreshToken::deleteAll(['refresh_token' => $tokenId]);
    }

    /**
     * @param $tokenId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByRefreshToken($tokenId)
    {
        return RefreshToken::find()
            ->where(['refresh_token' => $tokenId])
            ->one();
    }

    /**
     * @param $client_id
     * @param $grant_type
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByClientId($client_id, $grant_type, $member_id)
    {
        return RefreshToken::find()
            ->where([
                'client_id' => $client_id,
                'grant_type' => $grant_type,
                'member_id' => $member_id
            ])
            ->one();
    }
}