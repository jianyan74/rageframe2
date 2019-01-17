<?php
namespace backend\controllers;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii\filters\AccessControl;

/**
 * 基类控制器
 *
 * Class MController
 * @package backend\controllers
 * @property \yii\db\ActiveRecord $modelClass;
 * @property \EasyWeChat\OfficialAccount\Application $app;
 * @property \common\services\Application $services;
 * @author jianyan74 <751393839@qq.com>
 */
class MController extends \common\controllers\BaseController
{
    /**
     * 微信实例化SDK
     *
     * @var
     */
    protected $app;

    /**
     * 服务
     *
     * @var
     */
    protected $services;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        empty($this->app) && $this->app = Yii::$app->wechat->app;
        empty($this->services) && $this->services = Yii::$app->services;
    }

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
        if (!Yii::$app->user->isGuest && Yii::$app->user->id === Yii::$app->params['adminAccount'])
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

        if (in_array($permissionName, Yii::$app->params['noAuthRoute']) || in_array(Yii::$app->controller->action->id, Yii::$app->params['noAuthAction']) )
        {
            return true;
        }

        if (!Yii::$app->user->can($permissionName) && Yii::$app->getErrorHandler()->exception === null)
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