<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use common\controllers\AddonsController;
use addons\RfExample\common\components\Job;

/**
 * 消息队列控制器
 *
 * Class QueueController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class QueueController extends AddonsController
{
    /**
     * 队列推送demo
     * 注意: RabbitMQ 驱动不支持作业状态。
     *
     * @return mixed|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post()) {
            // 直接推入队列
            // 功能把内容写入文件
            $id = Yii::$app->queue->push(new Job([
                'content' => '这是一个测试的队列 ' . Yii::$app->formatter->asDatetime(time()),
                'file' => Yii::getAlias("@runtime") . '\queue-test.txt',
            ]));

            // 将作业推送到队列中延时5分钟运行
            // $id = Yii::$app->queue->delay(5 * 60)->push(new Job([
            //     'content' => '这是一个测试的队列 ' . Yii::$app->formatter->asDatetime(time()),
            //     'file' => Yii::getAlias("@runtime") . '\queue-test.txt',
            // ]));

            // 这个作业等待执行。
            // Yii::$app->queue->isWaiting($id);

            // Worker 从队列获取作业，并执行它。
            // Yii::$app->queue->isReserved($id);

            // Worker 作业执行完成。
            // Yii::$app->queue->isDone($id);

            return $this->message('推送成功', $this->redirect(['index']));
        }

        return $this->render($this->action->id, [

        ]);
    }
}