<?php

namespace services\sys;

use Yii;
use yii\helpers\Json;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\sys\Notify;
use common\models\sys\NotifyManager;
use common\enums\SubscriptionAlertTypeEnum;
use common\models\sys\NotifySubscriptionConfig;

/**
 * Class NotifyService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyService extends Service
{
    /**
     * 创建公告
     *
     * @param string $content
     * @param int $sender_id
     */
    public function createAnnounce($content, $sender_id)
    {
        $model = new Notify();
        $model->content = $content;
        $model->sender_id = $sender_id;
        $model->type = Notify::TYPE_ANNOUNCE;
        return $model->save();
    }

    /**
     * 创建提醒
     *
     * @param int $target_id 触发id
     * @param string $targetType 触发类型
     * @param string $action 提醒关联动作
     * @param int $sender_id 发送者(用户)id
     * @param string $content 内容
     */
    public function createRemind($target_id, $targetType, $action, $sender_id, $content)
    {
        $model = new Notify();
        $model->target_id = $target_id;
        $model->target_type = $targetType;
        $model->content = $content;
        $model->sender_id = $sender_id;
        $model->action = $action;
        $model->type = Notify::TYPE_REMIND;
        return $model->save();
    }

    /**
     * 创建一条信息(私信)
     *
     * @param int $sender_id 触发id
     * @param string $content 内容
     * @param int $receiver 接收id
     */
    public function createMessage($content, $sender_id, $receiver)
    {
        $model = new Notify();
        $model->content = $content;
        $model->sender_id = $sender_id;
        $model->type = Notify::TYPE_MESSAGE;
        if ($model->save()) {
            $notifyManager = new NotifyManager();
            $notifyManager->notify_id = $model->id;
            $notifyManager->manager_id = $receiver;
            $notifyManager->type = Notify::TYPE_MESSAGE;
            return $notifyManager->save();
        }

        return false;
    }

    /**
     * 拉取公告
     *
     * @param int $manager_id 用户id
     * @throws \yii\db\Exception
     */
    public function pullAnnounce($manager_id, $created_at)
    {
        // 从UserNotify中获取最近的一条公告信息的创建时间: lastTime
        $model = NotifyManager::find()
            ->where(['manager_id' => $manager_id, 'type' => Notify::TYPE_ANNOUNCE])
            ->orderBy('id desc')
            ->asArray()
            ->one();

        // 用lastTime作为过滤条件，查询Notify的公告信息
        $lastTime = $model ? $model['created_at'] : $created_at;
        $notifys = Notify::find()
            ->where(['type' => Notify::TYPE_ANNOUNCE, 'status' => StatusEnum::ENABLED])
            ->andWhere(['>', 'created_at', $lastTime])
            ->asArray()
            ->all();

        // 新建UserNotify并关联查询出来的公告信息
        $rows = [];
        $fields = ['notify_id', 'manager_id', 'type', 'created_at', 'updated_at'];
        foreach ($notifys as $notify) {
            $rows[] = [$notify['id'], $manager_id, Notify::TYPE_ANNOUNCE, $notify['created_at'], time()];
        }

        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(NotifyManager::tableName(), $fields, $rows)->execute();
    }

    /**
     * 拉取提醒
     *
     * @param NotifySubscriptionConfig $subscriptionConfig
     * @param string $type
     */
    public function pullRemind(NotifySubscriptionConfig $subscriptionConfig, $type = SubscriptionAlertTypeEnum::SYS)
    {
        /** @var array $action 查询用户的配置文件SubscriptionConfig */
        $action = $subscriptionConfig->action;
        $filt = [];
        foreach ($action as $key => $item) {
            // 默认拉取系统通知
            if ($key == $type) {
                foreach ($item as $index => $value) {
                    $value == true && $filt[] = $index;
                }
            }
        }

        // 查询最后的一条提醒时间
        $lastTime = Yii::$app->services->sysNotifyPullTime->getLastTime($subscriptionConfig->manager_id, Notify::TYPE_REMIND, $type);
        // 直接通过自己的关注去拉取消息
        $notifys = Notify::find()
            ->where(['type' => Notify::TYPE_REMIND, 'status' => StatusEnum::ENABLED])
            ->andWhere(['in', 'action', $filt])
            ->andWhere(['>', 'created_at', $lastTime])
            ->asArray()
            ->all();

        // 使用过滤好的Notify作为关联新建UserNotify
        foreach ($notifys as $notify) {
            $notifyManager = new NotifyManager();
            $notifyManager->notify_id = $notify['id'];
            $notifyManager->manager_id = $subscriptionConfig->manager_id;
            $notifyManager->type = Notify::TYPE_REMIND;
            $notifyManager->save();
        }

        return $notifys;
    }

    /**
     * 更新订阅配置
     *
     * @param $manager_id
     */
    public function updateSubscriptionConfig($manager_id)
    {
        $actions = [];
        $config = NotifySubscriptionConfig::findOne(['manager_id' => $manager_id]);
        $config->action = Json::encode($actions);
        return $config->save();
    }

    /**
     * 获取用户消息列表
     *
     * @param $manager_id
     */
    public function getUserNotify($manager_id, $is_read = 0)
    {
        $data = NotifyManager::find()
            ->where(['status' => StatusEnum::ENABLED, 'is_read' => $is_read])
            ->andWhere(['manager_id' => $manager_id]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10]);
        $models = $data->offset($pages->offset)
            ->with('notify')
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        foreach ($models as &$model) {
            $model['type'] = Notify::$typeExplain[$model['type']];
        }

        return [$models, $pages];
    }

    /**
     * 更新指定的notify，把isRead属性设置为true
     *
     * @param $manager_id
     */
    public function read($manager_id, $notifyIds)
    {
        NotifyManager::updateAll(['is_read' => true, 'updated_at' => time()], ['and', ['manager_id' => $manager_id], ['in', 'notify_id', $notifyIds]]);
    }
}