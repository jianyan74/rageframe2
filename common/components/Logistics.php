<?php

namespace common\components;

use Yii;
use yii\web\NotFoundHttpException;
use Finecho\Logistics\OrderInterface;

/**
 * 快递查询
 *
 * $order = Yii::$app->logistics->aliyun();
 *
 * $order->getCode(); // 状态码
 * $order->getMsg(); // 状态信息
 * $order->getCompany(); // 物流公司简称
 * $order->getNo(); // 物流单号
 * $order->getStatus(); // 当前物流单状态
 *
 * 注：物流状态可能不一定准确
 *
 * $order->getDisplayStatus(); // 当前物流单状态展示名
 * $order->getAbstractStatus(); // 当前抽象物流单状态
 * $order->getCourier(); // 快递员姓名
 * $order->getCourierPhone(); // 快递员手机号
 * $order->getList(); // 物流单状态详情
 * $order->getOriginal(); // 获取接口原始返回信息
 *
 * Class Logistics
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Logistics extends Service
{
    protected $config;

    public function init()
    {
        $defaultConfig = Yii::$app->debris->backendConfigAll();
        $this->config = [
            'aliyun' => [
                'app_code' => $defaultConfig['logistics_aliyun_app_code'] ?? '',
            ],
            'juhe' => [
                'app_code' => $defaultConfig['logistics_juhe_app_code'] ?? '',
            ],
            'kdniao' => [
                'app_code' => $defaultConfig['logistics_kdniao_app_id'] ?? '',
                'customer' => $defaultConfig['logistics_kdniao_app_key'] ?? '',
            ],
            'kd100' => [
                'app_code' => $defaultConfig['logistics_kd100_app_id'] ?? '',
                'customer' => $defaultConfig['logistics_kd100_app_key'] ?? '',
            ],
        ];

        unset($defaultConfig);

        parent::init();
    }

    /**
     * 阿里云
     *
     * @param string $no 快递单号
     * @param null $company
     * @return OrderInterface
     */
    public function aliyun($no, $company = null, $isCache = false)
    {
        return $this->query($no, $company, 'aliyun', $isCache);
    }

    /**
     * 聚合
     *
     * @param string $no 快递单号
     * @param string $company 可选（建议必填，不填查询结果不一定准确）
     * @return OrderInterface
     */
    public function juhe($no, $company, $isCache = false)
    {
        return $this->query($no, $company, 'juhe', $isCache);
    }

    /**
     * 快递鸟
     *
     * @param string $no 快递单号
     * @param string $company 可选（建议必填，不填查询结果不一定准确）
     * @return OrderInterface
     */
    public function kdniao($no, $company = null, $isCache = false)
    {
        return $this->query($no, $company, 'kdniao', $isCache);
    }

    /**
     * 快递100
     *
     * @param string $no 快递单号
     * @param string $company 可选（建议必填，不填查询结果不一定准确）
     * @return OrderInterface
     */
    public function kd100($no, $company = null, $isCache = false)
    {
        return $this->query($no, $company, 'kd100', $isCache);
    }

    /**
     * 获取对应的可用快递公司名称
     *
     * @param $provider
     * @throws NotFoundHttpException
     */
    public function companies($provider)
    {
        if (!in_array($provider, array_keys($this->config))) {
            throw new NotFoundHttpException('找不到可用的快递类型');
        }

        return $this->logistics($provider)->companies();
    }

    /**
     * 查询
     *
     * @param $no
     * @param $company
     * @param $provider
     * @return mixed
     */
    protected function query($no, $company, $provider, $isCache)
    {
        if ($isCache == false) {
            return $this->logistics($provider)->query($no, $company);
        }

        $key = 'Logistics|' .  $no;
        if (!($data = Yii::$app->cache->get($key))) {
            $data = $this->logistics($provider)->query($no, $company);
            Yii::$app->cache->set($key, $data, 60 * 60);
        }

        return $data;
    }

    /**
     * @param $provider
     * @return \Finecho\Logistics\Logistics
     */
    protected function logistics($provider)
    {
        $this->config['provider'] = $provider;

        return new \Finecho\Logistics\Logistics($this->config);
    }
}