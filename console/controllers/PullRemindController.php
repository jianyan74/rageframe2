<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\sys\NotifySubscriptionConfig;
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
     * 钉钉提醒
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionDingTalk()
    {
        $list = Yii::$app->services->sysNotifySubscriptionConfig->getListWithManager();
        /** @var NotifySubscriptionConfig $item */
        foreach ($list as $item) {
            if (!empty($item->manager)) {
                $result = Yii::$app->services->sysNotify->pullRemind($item, SubscriptionAlertTypeEnum::DINGTALK);

                if ($result && !empty($item->manager->dingtalk_robot_token)) {
                    $text = [];
                    foreach ($result as $value) {
                        $text[] = '#' . $value['id'] . ' ' . $value['content'];
                    }

                    $text = implode("\n", $text);

                    try {
                        $robot = Robot::create($item->manager->dingtalk_robot_token);
                        $robot->send([
                            'msgtype' => 'markdown',
                            'markdown' => [
                                'title' => '消息提醒',
                                'text' => $text,
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
     * 系统提醒
     */
    public function actionSys()
    {
        $list = Yii::$app->services->sysNotifySubscriptionConfig->getListWithManager();
        /** @var NotifySubscriptionConfig $item */
        foreach ($list as $item) {
            Yii::$app->services->sysNotify->pullRemind($item);
        }
    }
}