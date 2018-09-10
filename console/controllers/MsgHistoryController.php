<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\wechat\Setting;
use common\models\wechat\MsgHistory;

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
        // 获取参数配置并判断是否开启了清理历史记录
        if(($history = Setting::getData('history')) && $history['msg_history_date']['value'] > 0)
        {
            $oneDay = 60 * 60 * 24;
            $time = time() - $oneDay * $history['msg_history_date']['value'];
            MsgHistory::deleteAll(['<=', 'append', $time]);

            echo date('Y-m-d H:i:s') . ' --- ' . '清理成功;' . PHP_EOL;
            exit();
        }

        echo date('Y-m-d H:i:s') . ' --- ' . '数据设置未清除;' . PHP_EOL;
        exit();
    }
}