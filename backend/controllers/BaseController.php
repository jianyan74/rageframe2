<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UnauthorizedHttpException;
use common\components\BaseAction;
use common\helpers\Auth;
use common\behaviors\ActionLogBehavior;

/**
 * Class BaseController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BaseController extends Controller
{
    use BaseAction;

    /**
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
                        'roles' => ['@'], // 登录
                    ],
                ],
            ],
            'actionLog' => [
                'class' => ActionLogBehavior::class
            ]
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws UnauthorizedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 判断当前模块的是否为主模块, 模块+控制器+方法
        $permissionName = '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        if (Yii::$app->controller->module->id != Yii::$app->id) {
            $permissionName = '/' . Yii::$app->controller->module->id . $permissionName;
        }

        // 判断是否忽略校验
        if (in_array($permissionName, Yii::$app->params['noAuthRoute'])) {
            return true;
        }

        // 开始权限校验
        if (!Auth::verify($permissionName)) {
            throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }

        return true;
    }
}