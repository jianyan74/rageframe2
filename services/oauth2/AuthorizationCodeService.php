<?php
namespace services\oauth2;

use common\components\Service;
use common\models\oauth2\AuthorizationCode;

/**
 * Class AuthorizationCodeService
 * @package services\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class AuthorizationCodeService extends Service
{
    /**
     * @param $client_id
     * @param $authorization_code
     * @param $expires
     */
    public function create($client_id, $authorization_code, $expires, $member_id, $scopes)
    {
        if (!($model = $this->findByClientId($client_id))) {
            $model = new AuthorizationCode();
            $model->client_id = $client_id;
        }

        $model->expires = $expires;
        $model->authorization_code = $authorization_code;
        $model->member_id = (string) $member_id;
        $model->scope = $scopes;
        $model->save();
    }

    /**
     * @param $tokenId
     */
    public function deleteByAuthorizationCode($tokenId)
    {
        AuthorizationCode::deleteAll(['authorization_code' => $tokenId]);
    }

    /**
     * @param $tokenId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByAuthorizationCode($tokenId)
    {
        return AuthorizationCode::find()
            ->where(['authorization_code' => $tokenId])
            ->one();
    }

    /**
     * @param $client_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByClientId($client_id)
    {
        return AuthorizationCode::find()
            ->where(['client_id' => $client_id])
            ->one();
    }
}