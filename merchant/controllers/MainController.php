<?php

namespace merchant\controllers;

use Yii;

/**
 * 主控制器
 *
 * Class MainController
 * @package merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MainController extends BaseController
{
    /**
     * 系统首页
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial($this->action->id, [
        ]);
    }

    /**
     * 子框架默认主页
     *
     * @return string
     */
    public function actionSystem()
    {
        return $this->render($this->action->id, [
        ]);
    }
}