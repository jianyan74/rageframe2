<?php

namespace common\interfaces;

/**
 * 插件模块设置接口
 *
 * Interface AddonsSetting
 * @package common\interfaces
 */
interface AddonsSetting
{
    /**
     * 默认设置方法
     *
     * @return mixed
     */
    public function actionDisplay();

    /**
     * 默认钩子方法
     *
     * @param array $param
     * @return mixed
     */
    public function actionHook($param = []);
}