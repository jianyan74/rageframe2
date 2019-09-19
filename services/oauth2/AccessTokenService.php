<?php

namespace services\oauth2;

use common\models\oauth2\AccessToken;
use common\components\Service;

/**
 * Class AccessTokenService
 * @package services\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenService extends Service
{
    /**
     * @param $client_id
     * @param $grant_type
     * @param $access_token
     * @param $expires
     * @param $member_id
     * @param $scopes
     */
    public function create($client_id, $grant_type, $access_token, $expires, $member_id, $scopes)
    {
        if (!($model = $this->findByClientId($client_id, $grant_type))) {
            $model = new AccessToken();
            $model->client_id = $client_id;
        }

        $model->expires = $expires;
        $model->grant_type = $grant_type;
        $model->access_token = $access_token;
        $model->member_id = (string)$member_id;
        $model->scope = $scopes;
        $model->save();
    }

    /**
     * @param $tokenId
     */
    public function deleteByAccessToken($tokenId)
    {
        AccessToken::deleteAll(['access_token' => $tokenId]);
    }

    /**
     * @param $tokenId
     * @param string $client_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByAccessToken($tokenId, $client_id = '')
    {
        return AccessToken::find()
            ->where(['access_token' => $tokenId])
            ->andFilterWhere(['client_id' => $client_id])
            ->one();
    }

    /**
     * @param $client_id
     * @param $grant_type
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByClientId($client_id, $grant_type)
    {
        return AccessToken::find()
            ->where(['client_id' => $client_id, 'grant_type' => $grant_type])
            ->one();
    }
}