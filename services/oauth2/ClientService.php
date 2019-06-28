<?php
namespace services\oauth2;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\oauth2\Client;

/**
 * Class ClientService
 * @package services\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class ClientService extends Service
{
    /**
     * @param $client_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByClientId($client_id)
    {
        return Client::find()
            ->where(['client_id' => $client_id, 'status' => StatusEnum::ENABLED])
            ->one();
    }
}