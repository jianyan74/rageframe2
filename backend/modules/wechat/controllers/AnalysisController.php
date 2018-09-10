<?php
namespace backend\modules\wechat\controllers;

use Yii;
use linslin\yii2\curl;

/**
 * Curl获取微信图片(防盗链地址)
 *
 * Class AnalysisController
 * @package backend\modules\wechat\controllers
 */
class AnalysisController extends WController
{
    /**
     * 获取微信图片
     */
    public function actionImage()
    {
        $image = Yii::$app->request->get('attach');
        $curl = new curl\Curl();
        $response = $curl->get($image);
        header('Content-Type:image/jpg');
        echo $response;
        exit();
    }
}