<?php

namespace addons\Wechat\console\controllers;

use Yii;
use yii\console\Controller;
use common\enums\StatusEnum;
use addons\Wechat\common\models\MassRecord;

/**
 * Class SendMessageController
 * @package console\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SendMessageController extends Controller
{
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionIndex()
    {
        $models = MassRecord::find()
            ->where(['send_status' => StatusEnum::DISABLED])
            ->andWhere(['<=', 'send_time', time()])
            ->all();

        /** @var MassRecord $record */
        foreach ($models as $record) {
            if (Yii::$app->wechatService->message->send($record)) {
                $this->stdout(date('Y-m-d H:i:s') . ' --- ' . '发送成功, 所属商户ID:' . $record->merchant_id . PHP_EOL);
            } else {
                $this->stderr(date('Y-m-d H:i:s') . ' --- ' . '发送失败, 所属商户ID:' . $record->merchant_id . PHP_EOL);
            }
        }

        exit();
    }
}