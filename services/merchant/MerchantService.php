<?php

namespace services\merchant;

use common\components\Service;
use common\enums\MerchantStateEnum;
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
     * @return int
     */
    public function getNotNullId(): int
    {
        return !empty($this->merchant_id) ? (int)$this->merchant_id : 0;
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
    public function getCount($merchant_id = '')
    {
        return Merchant::find()
            ->select('id')
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['state' => StatusEnum::ENABLED])
            ->andFilterWhere(['id' => $merchant_id])
            ->count();
    }

    /**
     * @return int|string
     */
    public function getApplyCount($merchant_id = '')
    {
        return Merchant::find()
            ->select('id')
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'state', [MerchantStateEnum::AUDIT]])
            ->andFilterWhere(['id' => $merchant_id])
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

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findBaseById($id)
    {
        return Merchant::find()
            ->select([
                'id',
                'title',
                'cover',
                'address_name',
                'address_details',
                'longitude',
                'latitude',
            ])
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findBaseByIds($ids)
    {
        return Merchant::find()
            ->select([
                'id',
                'title',
                'cover',
                'address_name',
                'address_details',
                'longitude',
                'latitude',
            ])
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'id', $ids])
            ->asArray()
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findBaseAll()
    {
        return Merchant::find()
            ->select([
                'id',
                'title',
                'cover',
                'address_name',
                'address_details',
                'longitude',
                'latitude',
            ])
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->asArray()
            ->all();
    }
}