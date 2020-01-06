<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use linslin\yii2\curl;
use yii\web\NotFoundHttpException;

/**
 * Class AnalysisController
 * @package addons\Wechat\merchant\controllers
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
        $imgUrl = str_replace("&amp;", "&", htmlspecialchars($imgUrl));
        // http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            throw new NotFoundHttpException('不是一个http地址');
        }

        preg_match('/(^https?:\/\/[^:\/]+)/', $imgUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';
        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
            throw new NotFoundHttpException('Url不合法');
        }
        // 获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            throw new NotFoundHttpException('文件获取失败');
        }
        // Content-Type验证)
        if (!isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
            throw new NotFoundHttpException('格式验证失败');
        }

        $curl = new curl\Curl();
        $response = $curl->get($imgUrl);
        header('Content-Type:image/jpg');
        echo $response;
        exit();
    }
}