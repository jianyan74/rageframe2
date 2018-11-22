<?php
namespace common\controllers;

use Yii;
use common\helpers\AddonHelper;
use common\helpers\StringHelper;
use common\helpers\AddonUrl;

/**
 * 模块基类控制器
 *
 * Class AddonBaseController
 * @package common\controllers
 */
class AddonsBaseController extends BaseController
{
    /**
     * @var string
     */
    public $layout = null;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        // 后台视图默认载入模块视图
        if (!$this->layout)
        {
            switch (Yii::$app->params['addonInfo']['moduleId'])
            {
                case 'backend':
                    $this->layout = '@backend/views/layouts/addon';
                    break;
                case 'wechat':
                    $this->layout = '@wechat/views/layouts/main';
                    break;
                case 'frontend':
                    $this->layout = '@frontend/views/layouts/main';
                    break;
                case 'api':
                    $this->layout = '@api/views/layouts/main';
                    break;
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
        return Yii::$app->getResponse()->redirect(AddonUrl::to($url), $statusCode);
    }

    /**
     * 匹配对应的渲染的视图
     *
     * @param $view
     */
    protected function analyView($view)
    {
        // 判断是否有自定义的路径
        if (strncmp($view, '@', 1) === 0)
        {
            return $view;
        }

        // 判断路由是否写全
        $controller = '';
        if (count(explode('/', $view)) == 1)
        {
            $controller = StringHelper::toUnderScore(Yii::$app->params['addonInfo']['controller']) . '/';
        }

        // 注入资源文件
        $this->registerClientResource();

        return "@addons" . '/'. Yii::$app->params['addonInfo']['name'] . '/' . AddonHelper::getAppName() . '/views/' . $controller . $view;
    }

    /**
     * 注入资源文件
     */
    private function registerClientResource()
    {
        $assetsPath = "addons" . '\\'. Yii::$app->params['addonInfo']['name'] . '\\' . AddonHelper::getAppName() . '\\assets';
        $assets = $assetsPath . '\\Asset';

        // 注册资源类名
        Yii::$app->params['addonInfo']['assetBundlesName'] = $assets;
        $assets::register($this->getView());
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

    /**
     * 错误提示信息
     *
     * @param string $msgText 错误内容
     * @param string $skipUrl 跳转链接
     * @param null $msgType 提示类型[success/error/info/warning]
     * @param int $closeTime 提示关闭时间
     * @return mixed
     */
    protected function message($msgText, $skipUrl, $msgType = null, int $closeTime = 5)
    {
        $msgType = $msgType ?? 'success';
        $html = $msgText . " <span class='rfCloseTime'>" . $closeTime . "</span>秒后自动关闭...";

        Yii::$app->getSession()->setFlash($msgType, $html);

        return $skipUrl;
    }
}