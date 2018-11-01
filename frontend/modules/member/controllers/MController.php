<?php
namespace frontend\modules\member\controllers;

use frontend\controllers\IController;
use yii\filters\AccessControl;

/**
 * Class MController
 * @package frontend\modules\member\controllers
 */
class MController extends IController
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