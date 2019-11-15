<?php

namespace common\interfaces;

/**
 * 小工具接口
 *
 * Interface AddonWidget
 * @package common\interfaces
 */
interface AddonWidget
{
    /**
     * 运行方法
     *
     * @param $params
     * @return mixed
     */
    public function run($params);
}