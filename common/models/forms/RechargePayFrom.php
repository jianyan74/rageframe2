<?php

namespace common\models\forms;

use yii\base\Model;
use common\helpers\StringHelper;
use common\interfaces\PayHandler;

/**
 * Class RechargePayFrom
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class RechargePayFrom extends Model implements PayHandler
{
    /**
     * @var
     */
    public $money;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['money', 'required'],
            ['money', 'number', 'min' => 0.01],
        ];
    }

    /**
     * 支付说明
     *
     * @return string
     */
    public function getBody(): string
    {
        return '在线充值';
    }

    /**
     * 支付详情
     *
     * @return string
     */
    public function getDetails(): string
    {
        return '';
    }

    /**
     * 支付金额
     *
     * @return float
     */
    public function getTotalFee(): float
    {
        return $this->money;
    }

    /**
     * 获取订单号
     *
     * @return float
     */
    public function getOrderSn(): string
    {
        return StringHelper::randomNum(time());
    }

    /**
     * 是否查询订单号(避免重复生成)
     *
     * @return bool
     */
    public function isQueryOrderSn(): bool
    {
        return false;
    }
}