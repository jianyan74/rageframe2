<?php
namespace backend\interfaces;

/**
 * 插件模块设置接口
 *
 * Interface AddonsSettingInterface
 * @package backend\interfaces
 */
interface AddonsSettingInterface
{
    /**
     * @return mixed
     */
    public function actionDisplay();

    /**
     * @param array $param
     * @return mixed
     */
    public function actionHook($param = []);
}