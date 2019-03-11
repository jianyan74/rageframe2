<?php
namespace backend\controllers;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii\filters\AccessControl;
use common\helpers\AuthHelper;

/**
 * 基类控制器
 *
 * Class MController
 * @package backend\controllers
 * @property \yii\db\ActiveRecord $modelClass;
 * @author jianyan74 <751393839@qq.com>
 */
class MController extends \common\controllers\BaseController
{
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
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * RBAC验证
     *
     * @param $action
     * @return bool
     * @throws UnauthorizedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        // 分页
        Yii::$app->debris->config('sys_page') && $this->pageSize = Yii::$app->debris->config('sys_page');

        // 验证是否登录且验证是否超级管理员
        if (!Yii::$app->user->isGuest && Yii::$app->services->sys->isAuperAdmin())
        {
            return true;
        }

        if (!parent::beforeAction($action))
        {
            return false;
        }

        // 控制器+方法
        $permissionName = '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        // 加入模块验证
        if (Yii::$app->controller->module->id != "app-backend")
        {
            $permissionName = '/' . Yii::$app->controller->module->id . $permissionName;
        }

        // 判断是否忽略校验
        if (in_array($permissionName, Yii::$app->params['noAuthRoute']))
        {
            return true;
        }

        // 开始权限校验
        if (!AuthHelper::verify($permissionName))
        {
            throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }

        return true;
    }

    /**
     * 错误提示信息
     *
     * @param string $msgText 错误内容
     * @param string $skipUrl 跳转链接
     * @param string $msgType 提示类型 [success/error/info/warning]
     * @return mixed
     */
    public function message($msgText, $skipUrl, $msgType = null)
    {
        $msgType = $msgType ?? 'success';
        !in_array($msgType, ['success', 'error', 'info', 'warning']) && $msgType = 'success';

        Yii::$app->getSession()->setFlash($msgType, $msgText);

        return $skipUrl;
    }
}