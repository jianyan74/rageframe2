<?php

namespace common\controllers;

use Yii;
use yii\web\Controller;
use common\helpers\AddonHelper;
use common\helpers\StringHelper;
use common\helpers\Url;
use common\enums\AuthEnum;
use common\components\BaseAction;

/**
 * 模块基类控制器
 *
 * Class AddonsController
 * @package common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsController extends Controller
{
    use BaseAction;

    /**
     * 视图自动加载文件路径
     *
     * @var string
     */
    public $layout = null;

    /**
     * 是否钩子
     *
     * @var bool
     */
    public $isHook = false;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        // 后台视图默认载入模块视图
        if (!$this->layout) {
            $this->layout = '@' . Yii::$app->id . '/views/layouts/main';

            if (Yii::$app->id == AuthEnum::TYPE_BACKEND) {
                $this->layout = '@' . Yii::$app->id . '/views/layouts/addon';
            }
        }
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        $content = $this->getView()->render($this->analyView($view), $params, $this);
        return $this->renderContent($content);
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function renderPartial($view, $params = [])
    {
        return $this->getView()->render($this->analyView($view), $params, $this);
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function renderAjax($view, $params = [])
    {
        return $this->getView()->renderAjax($this->analyView($view), $params, $this);
    }

    /**
     * 跳转
     *
     * @param array|string $url
     * @param int $statusCode
     * @return \yii\console\Response|\yii\web\Response
     */
    public function redirect($url, $statusCode = 302)
    {
        return Yii::$app->getResponse()->redirect(Url::to($url), $statusCode);
    }

    /**
     * 匹配对应的渲染的视图
     *
     * @param $view
     */
    protected function analyView($view)
    {
        // 判断是否有自定义的路径
        if (strncmp($view, '@', 1) === 0) {
            return $view;
        }

        // 判断路由是否写全
        $controller = '';
        if (count(explode('/', $view)) == 1) {
            $controller = StringHelper::toUnderScore(Yii::$app->params['addonInfo']['controller']) . '/';
        }

        $appId = $this->isHook == true ? 'backend' : Yii::$app->id;
        return "@addons" . '/' . Yii::$app->params['addonInfo']['name'] . '/' . $appId . '/views/' . $controller . $view;
    }

    /**
     * 获取配置信息
     *
     * @return mixed
     */
    protected function getConfig()
    {
        return AddonHelper::getConfig();
    }

    /**
     * 写入配置信息
     *
     * @param $config
     * @return bool
     */
    protected function setConfig($config)
    {
        return AddonHelper::setConfig($config);
    }
}