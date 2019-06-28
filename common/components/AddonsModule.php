<?php

namespace common\components;

use Yii;
use yii\web\UnauthorizedHttpException;
use common\helpers\StringHelper;
use common\helpers\AddonHelper;
use common\helpers\Auth;
use common\enums\AuthEnum;
use common\helpers\ExecuteHelper;

/**
 * 插件模块
 *
 * Class Module
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsModule extends \yii\base\Module
{
    /**
     * 路由
     *
     * @var string
     */
    private $_route;

    /**
     * @return bool|void
     * @throws UnauthorizedHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function init()
    {
        parent::init();
        $appId = Yii::$app->id;
        $requestedRoute = $this->module->requestedRoute;
        $arr = explode('/', $requestedRoute);

        // 判断是否直接访问addons控制器
        if (count($arr) <= 2) {
            $controllerNamespace = [];
            $controllerNamespace[] = $appId;
            $controllerNamespace[] = 'controllers';
            $this->controllerNamespace = implode('\\', $controllerNamespace);
            $this->_route = implode('/', $arr);
            return;
        }

        $name = $arr[1];
        $name = StringHelper::strUcwords($name);
        unset($arr[0], $arr[1]);
        $this->_route = implode('/', $arr);


        // 初始化模块
        AddonHelper::initAddon($name, $this->_route);
        // 解析路由
        AddonHelper::analysisRoute($this->_route, $appId);
        // 后台校验权限
        $appId == AuthEnum::TYPE_BACKEND && $this->verify();
        // 引导执行
        $this->bootstrap();

        $controllerNamespace = [];
        $controllerNamespace[] = 'addons';
        $controllerNamespace[] = $name;
        $controllerNamespace[] = $appId;
        $controllerNamespace[] = 'controllers';
        $this->controllerNamespace = implode('\\', $controllerNamespace);

        Yii::$classMap['yii\data\Sort'] = '@common/replaces/Sort.php'; // 排序
        Yii::$classMap['yii\widgets\Breadcrumbs'] = '@common/replaces/Breadcrumbs.php'; // 面包屑
        Yii::$classMap['yii\data\Pagination'] = '@common/replaces/Pagination.php'; // 分页
    }

    /**
     * 执行引导
     *
     * @throws \yii\web\NotFoundHttpException
     */
    protected function bootstrap()
    {
        if (!empty(Yii::$app->params['addon']['bootstrap'])) {
            ExecuteHelper::map(Yii::$app->params['addon']['bootstrap'], 'run', Yii::$app->params['addon']);
        }
    }

    /**
     * 权限校验
     *
     * @throws UnauthorizedHttpException
     */
    protected function verify()
    {
        if (Yii::$app->user->isGuest) {
            throw new UnauthorizedHttpException('未登录');
        }

        if (false === Auth::verify($this->_route)) {
            throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }
    }

    /**
     * 切换路由
     *
     * @param string $route
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function createController($route)
    {
        $route = $this->_route;
        return parent::createController($route);
    }
}
