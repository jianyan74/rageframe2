<?php
namespace backend\controllers;

use Yii;
use yii\caching\FileCache;
use common\models\sys\Style;
use common\models\sys\MenuCate;

/**
 * 主控制器
 *
 * Class MainController
 * @package backend\controllers
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
        $status = false;
        // 删除后台文件缓存
        Yii::$app->cache->flush();

        $frontend_cache_path = Yii::getAlias('@frontend') . '/runtime/cache';
        $wechat_cache_path = Yii::getAlias('@wechat') . '/runtime/cache';
        $api_cache_path = Yii::getAlias('@api') . '/runtime/cache';
        $console_cache_path = Yii::getAlias('@console') . '/runtime/cache';

        // 清理前台文件缓存
        if (is_dir($frontend_cache_path))
        {
            if (is_writable($frontend_cache_path))
            {
                $cache = new FileCache();
                $cache->cachePath = $frontend_cache_path;
                $cache->gc(true, false);
            }
            else
            {
                $status = $frontend_cache_path;
            }
        }

        // 清理微信文件缓存
        if (is_dir($wechat_cache_path))
        {
            if (is_writable($wechat_cache_path))
            {
                $cache = new FileCache();
                $cache->cachePath = $wechat_cache_path;
                $cache->gc(true, false);
            }
            else
            {
                $status = $wechat_cache_path;
            }
        }

        // 清理api文件缓存
        if (is_dir($api_cache_path))
        {
            if (is_writable($api_cache_path))
            {
                $cache = new FileCache();
                $cache->cachePath = $api_cache_path;
                $cache->gc(true, false);
            }
            else
            {
                $status = $api_cache_path;
            }
        }

        // 清理控制台文件缓存
        if (is_dir($console_cache_path))
        {
            if (is_writable($console_cache_path))
            {
                $cache = new FileCache();
                $cache->cachePath = $console_cache_path;
                $cache->gc(true, false);
            }
            else
            {
                $status = $console_cache_path;
            }
        }

        // 删除备份缓存
        $path = Yii::$app->params['dataBackupPath'];
        $lock = realpath($path) . DIRECTORY_SEPARATOR . Yii::$app->params['dataBackLock'];
        array_map("unlink", glob($lock));

        return $this->render('clear-cache', [
            'status' => $status
        ]);
    }
}