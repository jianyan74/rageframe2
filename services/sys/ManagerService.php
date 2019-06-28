<?php
namespace services\sys;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\sys\Manager;
use common\components\Service;

/**
 * Class ManagerService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class ManagerService extends Service
{
    /**
     * @return array
     */
    public function getMapList()
    {
        return ArrayHelper::map($this->getList(), 'id', 'username');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return Manager::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->all();
    }
}