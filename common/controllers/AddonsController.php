<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\helpers\AddonHelper;
use common\helpers\StringHelper;

/**
 * 模块插件渲染
 *
 * Class AddonsController
 * @package common\controllers
 */
class AddonsController extends Controller
{
    /**
     * @var string
     */
    public $layout = "@backend/views/layouts/addon";

    /**
     * 当前路由
     *
     * @var
     */
    public $route;

    /**
     * 模块名称
     *
     * @var
     */
    public $addonName;

    public function init()
    {
        parent::init();

        $this->route = Yii::$app->request->get('route', null) ?? Yii::$app->request->post('route');
        $this->addonName = Yii::$app->request->get('addon', null) ?? Yii::$app->request->post('addon');
        $this->addonName = StringHelper::strUcwords($this->addonName);
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        // 关闭csrf
        $action->id == 'execute' && $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 跳转插件详情页面
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidRouteException
     */
    public function actionExecute()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);
        // 解析路由
        AddonHelper::analysisRoute($this->route, AddonHelper::getAppName());

        // 替换
        Yii::$classMap['yii\data\Pagination'] = '@backend/components/Pagination.php';// 分页
        Yii::$classMap['yii\data\Sort'] = '@backend/components/Sort.php';// 排序

        // 实例化解获取数据
        return $this->rendering();
    }

    /**
     * 渲染
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidRouteException
     */
    protected function rendering()
    {
        $oldAction = Yii::$app->params['addonInfo']['oldAction'];
        $id = Yii::$app->params['addonInfo']['controller'];
        $controllersPath = Yii::$app->params['addonInfo']['controllersPath'];
        $parts = Yii::createObject($controllersPath, [$id, $this]);

        $params = Yii::$app->request->get();
        /* @var $controller \yii\base\Controller */
        list($controller, $actionID) = [$parts, $oldAction];
        $oldController = Yii::$app->controller;
        Yii::$app->controller = $controller;
        $result = $controller->runAction($actionID, $params);

        if ($oldController !== null)
        {
            Yii::$app->controller = $oldController;
        }

        return $result;
    }
}