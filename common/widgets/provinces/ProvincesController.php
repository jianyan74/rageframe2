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

        $str = "-- 请选择市 --";
        $model = Yii::$app->services->provinces->getCityMapByPid($pid);
        if ($type_id == 1 && !$pid) {
            return Html::tag('option', '-- 请选择市 --', ['value' => '']);
        } elseif ($type_id == 2 && !$pid) {
            return Html::tag('option', '-- 请选择区 --', ['value' => '']);
        } elseif ($type_id == 2 && $model) {
            $str = "-- 请选择区 --";
        }

        $str = Html::tag('option', $str, ['value' => '']);
        foreach ($model as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), ['value' => $value]);
        }

        return $str;
    }
}