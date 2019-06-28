<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use linslin\yii2\curl;

/**
 * Class AnalysisController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AnalysisController extends Controller
{
    /**
     * 行为控制
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws \Exception
     */
    public function actionImage()
    {
        $imgUrl = Yii::$app->request->get('attach');
        $curl = new curl\Curl();
        $response = $curl->get($imgUrl);
        header('Content-Type:image/jpg');
        echo $response;
        exit();
    }
}