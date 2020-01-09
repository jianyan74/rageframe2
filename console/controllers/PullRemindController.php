<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\backend\NotifySubscriptionConfig;
use common\enums\SubscriptionAlertTypeEnum;
use EasyDingTalk\Robot;

/**
 * Class PullRemindController
 * @package console\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PullRemindController extends Controller
{
    /**
     * 系统提醒
     */
    public function actionSys()
    {
        // 获取订阅的管理员列表
        $list = Yii::$app->services->backendNotifySubscriptionConfig->findAllWithMember();
        /** @var NotifySubscriptionConfig $item */
        foreach ($list as $item) {
            Yii::$app->services->backendNotify->pullRemind($item);
        }
    }

    /**
     * 钉钉提醒
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionDingTalk()
    {
        // 获取订阅的管理员列表
        $list = Yii::$app->services->backendNotifySubscriptionConfig->findAllWithMember();
        /** @var NotifySubscriptionConfig $item */
        foreach ($list as $item) {
            if (!empty($item->manager)) {
                $result = Yii::$app->services->backendNotify->pullRemind($item, SubscriptionAlertTypeEnum::DINGTALK);

                if ($result && !empty($item->manager->dingtalk_robot_token)) {
                    $text = [];
                    foreach ($result as $value) {
                        $text[] = '#' . $value['id'] . ' ' . $value['content'];
                    }

                    try {
                        $robot = Robot::create($item->manager->dingtalk_robot_token);
                        $robot->send([
                            'msgtype' => 'markdown',
                            'markdown' => [
                                'title' => '消息提醒',
                                'text' => implode("\n", $text),
                            ],
                            'at' => [
                                'atMobiles' => [
                                ],
                                'isAtAll' => false
                            ]
                        ]);
                    } catch (\Exception $e) {
                        Yii::error($e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * 微信消息模板提醒
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechat()
    {
        // 获取订阅的管理员列表
        $list = Yii::$app->services->backendNotifySubscriptionConfig->findAllWithMember();
        /** @var NotifySubscriptionConfig $item */
        foreach ($list as $item) {
            if (!empty($item->manager)) {
                $result = Yii::$app->services->backendNotify->pullRemind($item, SubscriptionAlertTypeEnum::WECHAT);

                if ($result && !empty($item->manager->openid)) {
                    $text = [];

                    foreach ($result as $value) {
                        $text[] = '#' . $value['id'] . ' ' . $value['content'];
                    }

                    $text = implode("\n", $text);

                    try {
                        // TODO 配置微信模板及通知人
                        Yii::$app->wechat->app->template_message->send([
                            'touser' => $item->manager->openid,
                            'template_id' => 'template-id',
                            'data' => [
                                'key1' => 'VALUE',
                                'key2' => 'VALUE2',
                            ],
                        ]);
                    } catch (\Exception $e) {
                        Yii::error($e->getMessage());
                    }
                }
            }
        }
    }
}