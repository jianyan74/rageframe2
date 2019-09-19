<?php

namespace services\sys;

use common\components\Service;
use common\models\sys\NotifyPullTime;

/**
 * Class NotifyPullTimeService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyPullTimeService extends Service
{
    /**
     * @param $manager_id
     * @param $type
     * @param string $alert_type
     * @return int|mixed
     */
    public function getLastTime($manager_id, $type, $alert_type = '')
    {
        $time = time();
        $model = $this->findByManagerId($manager_id, $type, $alert_type);
        if (!$model) {
            $model = new NotifyPullTime();
            $model->manager_id = $manager_id;
            $model->type = $type;
            $model->alert_type = $alert_type;
            $model->last_time = $time;
        } else {
            $time = $model->last_time;
            $model->last_time = time();
        }

        $model->save();
        return $time;
    }

    /**
     * @param $manager_id
     * @param $type
     * @param string $alert_type
     * @return array|\yii\db\ActiveRecord|null|NotifyPullTime
     */
    public function findByManagerId($manager_id, $type, $alert_type = '')
    {
        // 查询最新的一条提醒时间
        return NotifyPullTime::find()
            ->where(['manager_id' => $manager_id, 'type' => $type])
            ->andFilterWhere(['alert_type' => $alert_type])
            ->orderBy('last_time desc')
            ->one();
    }
}