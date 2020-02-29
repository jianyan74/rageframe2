<?php

namespace addons\Wechat\console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Json;
use addons\Wechat\common\models\MsgHistory;

/**
 * 定时任务历史消息清理
 *
 * Class MsgHistoryController
 * @package console\controllers
 */
class MsgHistoryController extends Controller
{
    /**
     * 清理过期的历史记录
     */
    public function actionIndex()
    {
        $models = Yii::$app->wechatService->setting->getList();
        foreach ($models as $record) {
            if ($history = Json::decode($record['history'])) {
                if ($history && $history['msg_history_date'] > 0) {
                    $oneDay = 60 * 60 * 24;
                    $time = time() - $oneDay * $history['msg_history_date'];
                    MsgHistory::deleteAll([
                        'and',
                        ['merchant_id' => $record->merchant_id],
                        ['<=', 'created_at', $time]
                    ]);

                    $this->stdout(date('Y-m-d H:i:s') . ' --- ' . '清理成功, 所属商户ID:' . $record->merchant_id . PHP_EOL);
                }
            }
        }

        $this->stdout('执行完毕');
    }
}