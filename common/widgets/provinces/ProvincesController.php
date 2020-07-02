<?php

namespace common\widgets\provinces;

use yii;
use yii\web\Response;
use common\helpers\Html;

/**
 * Class ProvincesController
 * @package common\widgets\provinces
 * @author jianyan74 <751393839@qq.com>
 */
class ProvincesController extends yii\web\Controller
{
    /**
     * 行为控制
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => yii\filters\AccessControl::class,
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
     * 首页
     */
    public function actionIndex($pid, $type_id = 0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Yii::$app->services->provinces->getCityMapByPid($pid);
        switch ($type_id) {
            case 1 :
                $str = "-- 请选择市 --";
                break;
            case 2 :
                $str = "-- 请选择区 --";
                break;
            case 3 :
                $str = "-- 请选择乡/镇 --";
                break;
            case 4 :
                $str = "-- 请选择村/社区 --";
                break;
        }

        if (!$pid) {
            switch ($type_id) {
                case 1 :
                    return Html::tag('option', '-- 请选择市 --', ['value' => '']);
                    break;
                case 2 :
                    return Html::tag('option', '-- 请选择区 --', ['value' => '']);
                    break;
                case 3 :
                    return Html::tag('option', '-- 请选择乡/镇 --', ['value' => '']);
                    break;
                case 4 :
                    return Html::tag('option', '-- 请选择村/社区 --', ['value' => '']);
                    break;
            }
        }

        $str = Html::tag('option', $str, ['value' => '']);
        foreach ($model as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), ['value' => $value]);
        }

        return $str;
    }
}