<?php

namespace services\merchant;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\merchant\Merchant;

/**
 * 商户
 *
 * Class MerchantService
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantService extends Service
{
    /**
     * @var int
     */
    protected $merchant_id = 1;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->merchant_id;
    }

    /**
     * @param $merchant_id
     */
    public function setId($merchant_id)
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * @param $merchant_id
     */
    public function addId($merchant_id)
    {
        !$this->merchant_id && $this->merchant_id = $merchant_id;
    }

    /**
     * @return int|string
     */
    public function getCount()
    {
        return Merchant::find()
            ->select('id')
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['state' => StatusEnum::ENABLED])
            ->count();
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByLogin()
    {
        return $this->findById($this->getId());
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return Merchant::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['id' => $id])
            ->one();
    }
}