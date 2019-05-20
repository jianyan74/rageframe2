<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;
use yii\filters\AccessControl;
use common\helpers\Auth;

/**
 * Class BaseController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BaseController extends Controller
{
    /**
     * 默认分页
     *
     * @var int
     */
    protected $pageSize = 10;

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
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 验证是否登录且验证是否超级管理员
        if (!Yii::$app->user->isGuest && Yii::$app->services->sys->isAuperAdmin()) {
            return true;
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

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    protected function analyErr($firstErrors)
    {
        return Yii::$app->debris->analyErr($firstErrors);
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
        if (!$msgType || !in_array($msgType, ['success', 'error', 'info', 'warning'])) {
            $msgType = 'success';
        }

        Yii::$app->getSession()->setFlash($msgType, $msgText);
        return $skipUrl;
    }
}