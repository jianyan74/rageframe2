<?php

namespace services\backend;

use common\components\Service;
use common\models\backend\NotifyPullTime;

/**
 * Class NotifyPullTimeService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyPullTimeService extends Service
{
    /**
     * @param $member_id
     * @param $type
     * @param string $alert_type
     * @return int|mixed
     */
    public function getLastTime($member_id, $type, $alert_type = '')
    {
        $time = time();
        $model = $this->findByMemberId($member_id, $type, $alert_type);
        if (!$model) {
            $model = new NotifyPullTime();
            $model->member_id = $member_id;
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
     * @param $member_id
     * @param $type
     * @param string $alert_type
     * @return array|\yii\db\ActiveRecord|null|NotifyPullTime
     */
    public function findByMemberId($member_id, $type, $alert_type = '')
    {
        // 查询最新的一条提醒时间
        return NotifyPullTime::find()
            ->where(['member_id' => $member_id, 'type' => $type])
            ->andFilterWhere(['alert_type' => $alert_type])
            ->orderBy('last_time desc')
            ->one();
    }
}