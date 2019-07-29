<?php

namespace backend\modules\wechat\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\Html;
use common\helpers\ResultDataHelper;

/**
 * 微信菜单地区控制器
 *
 * Class MenuProvincesController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MenuProvincesController extends Controller
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
     * @param $val
     * @return array
     */
    public function actionIndex($title)
    {
        $model = Yii::$app->services->wechatMenuProvinces->getListByTitle($title);

        $str = Html::tag('option', '不限', ['value' => '']);
        foreach ($model as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), ['value' => $value]);
        }

        return ResultDataHelper::json(200, '查询成功', $str);
    }
}