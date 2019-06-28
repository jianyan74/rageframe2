<?php
namespace backend\widgets\notify;

use Yii;
use yii\base\Widget;

/**
 * Class Notify
 * @package backend\widgets\notify
 * @author jianyan74 <751393839@qq.com>
 */
class Notify extends Widget
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function run()
    {
        // 拉取公告
        Yii::$app->services->sysNotify->pullAnnounce(Yii::$app->user->id, Yii::$app->user->identity->created_at);
        // 获取当前通知
        list($notify, $notifyPage) = Yii::$app->services->sysNotify->getUserNotify(Yii::$app->user->id);

        return $this->render('notify', [
            'notify' => $notify,
            'notifyPage' => $notifyPage,
        ]);
    }
}