<?php
namespace backend\controllers;

use yii\filters\AccessControl;

/**
 * Class AddonsController
 * @package backend\controllers
 */
class AddonsController extends \common\controllers\AddonsController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }
}