<?php
namespace backend\modules\wechat\controllers;

use yii\helpers\Html;
use common\helpers\ResultDataHelper;
use common\models\wechat\MenuProvinces;

/**
 * 微信菜单地区控制器
 *
 * Class MenuProvincesController
 * @package backend\modules\wechat\controllers
 */
class MenuProvincesController extends WController
{
    /**
     * @param $val
     * @return array
     */
    public function actionIndex($title)
    {
        $model = MenuProvinces::getMenuTitle($title);

        $str = Html::tag('option', '不限', ['value' => '']) ;
        foreach($model as $value => $name)
        {
            $str .= Html::tag('option', Html::encode($name), ['value' => $value]);
        }

        return ResultDataHelper::json(200, '查询成功', $str);
    }
}