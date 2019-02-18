<?php
namespace backend\controllers;

use Yii;
use common\models\sys\MenuCate;
use common\helpers\DebrisHelper;

/**
 * 主控制器
 *
 * Class MainController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MainController extends MController
{
    /**
     * 系统首页
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        // 判断是否手机
        Yii::$app->params['isMobile'] = DebrisHelper::isMobile();
        // 拉取公告
        Yii::$app->services->sys->notify->pullAnnounce(Yii::$app->user->id);
        // 获取当前通知
        list($notify, $notifyPage) = Yii::$app->services->sys->notify->getUserNotify(Yii::$app->user->id);

        return $this->renderPartial('index', [
            'menuCates' => MenuCate::getList(),
            'manager' => Yii::$app->user->identity,
            'notify' => $notify,
            'notifyPage' => $notifyPage,
        ]);
    }

    /**
     * 系统主页
     *
     * @return string
     */
    public function actionSystem()
    {
        return $this->render('system',[

        ]);
    }

    /**
     * 清理缓存
     */
    public function actionClearCache()
    {
        // 删除后台文件缓存
        $result = Yii::$app->cache->flush();

        // 删除备份缓存
        $path = Yii::$app->params['dataBackupPath'];
        $lock = realpath($path) . DIRECTORY_SEPARATOR . Yii::$app->params['dataBackLock'];
        array_map("unlink", glob($lock));

        return $this->render('clear-cache', [
            'result' => $result
        ]);
    }
}