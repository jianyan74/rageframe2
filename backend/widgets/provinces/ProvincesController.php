<?php
namespace backend\widgets\provinces;

use yii;
use yii\helpers\Html;
use yii\web\Response;
use common\models\common\Provinces;

/**
 * Class ProvincesController
 * @package backend\widgets\provinces
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
                'class' => yii\filters\AccessControl::className(),
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

        $model = Provinces::getCityList($pid);
        if($type_id == 1 && !$pid)
        {
            return Html::tag('option', '-- 请选择市 --', ['value' => '']) ;
        }
        else if($type_id == 2 && !$pid)
        {
            return Html::tag('option', '-- 请选择区 --', ['value' => '']) ;
        }
        else if($type_id == 2 && $model)
        {
            $str = "-- 请选择区 --";
        }

        $str = Html::tag('option', $str, ['value' => '']) ;
        foreach($model as $value => $name)
        {
            $str .= Html::tag('option', Html::encode($name), ['value' => $value]);
        }

        return $str;
    }
}