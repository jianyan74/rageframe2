<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use common\interfaces\PayHandler;

/**
 * Class OrderPayFrom
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class OrderPayFrom extends Model implements PayHandler
{
    /**
     * @var
     */
    public $order_id;

    protected $order;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['order_id', 'required'],
            ['order_id', 'integer', 'min' => 0],
            ['order_id', 'verifyPay'],
        ];
    }

    /**
     * @param $attribute
     */
    public function verifyPay($attribute)
    {
        // TODO 查询订单
        $this->order = '';
        if (!$this->order) {
            $this->addError($attribute, '找不到订单');

            return;
        }
    }

    /**
     * 支付说明
     *
     * @return string
     */
    public function getBody(): string
    {
        return '订单支付';
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
        return $this->order['pay_money'];
    }

    /**
     * 获取订单号
     *
     * @return float
     */
    public function getOrderSn(): string
    {
        return $this->order['order_sn'];
    }

    /**
     * 是否查询订单号(避免重复生成)
     *
     * @return bool
     */
    public function isQueryOrderSn(): bool
    {
        return true;
    }
}