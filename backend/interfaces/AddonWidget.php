<?php
namespace backend\interfaces;

/**
 * 小工具接口
 *
 * Interface AddonWidget
 * @package backend\interfaces
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