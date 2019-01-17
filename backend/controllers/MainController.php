<?php
namespace backend\controllers;

use Yii;
use common\models\sys\Style;
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
     * @return mixed
     */
    public function actionIndex()
    {
        // 判断是否手机
        Yii::$app->params['isMobile'] = DebrisHelper::isMobile();

        return $this->renderPartial('index',[
            'style' => Style::findByManagerId(Yii::$app->user->id),
            'menuCates' => MenuCate::getList()
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