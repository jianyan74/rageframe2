<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use common\enums\PayEnum;

/**
 * Class PayForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class PayForm extends Model
{
    public $orderGroup;
    public $payType;
    public $tradeType = 'default';
    public $data; // json数组
    public $memberId;
    public $returnUrl;
    public $notifyUrl;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['orderGroup', 'payType', 'data', 'tradeType', 'memberId'], 'required'],
            [['orderGroup'], 'in', 'range' => array_keys(PayEnum::$orderGroupExplain)],
            [['payType'], 'in', 'range' => array_keys(PayEnum::$payTypeExplain)],
            [['notifyUrl', 'returnUrl', 'data'], 'string'],
            [['tradeType'], 'verifyTradeType'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'orderGroup' => '订单组别',
            'data' => '组别对应数据',
            'payType' => '支付类别',
            'tradeType' => '交易类别',
            'memberId' => '用户id',
            'returnUrl' => '跳转地址',
            'notifyUrl' => '回调地址',
        ];
    }

    /**
     * 校验交易类型
     */
    public function verifyTradeType($attribute)
    {
        switch ($this->payType) {
            case PayEnum::PAY_TYPE :
                break;
            case PayEnum::PAY_TYPE_WECHAT :
                if (!in_array($this->tradeType, ['native', 'app', 'js', 'pos', 'mweb'])) {
                    $this->addError($attribute, '微信交易类型不符');
                }
                break;
            case PayEnum::PAY_TYPE_ALI :
                if (!in_array($this->tradeType, ['pc', 'app', 'f2f', 'wap'])) {
                    $this->addError($attribute, '支付宝交易类型不符');
                }
                break;
            case PayEnum::PAY_TYPE_MINI_PROGRAM :
                break;
            case PayEnum::PAY_TYPE_UNION :
                if (!in_array($this->tradeType, ['app', 'html'])) {
                    $this->addError($attribute, '银联交易类型不符');
                }
                break;
        }
    }

    /**
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     */
    public function getConfig()
    {
        $action = PayEnum::$payTypeAction[$this->payType];
        $baseOrder = $this->getBaseOrderInfo();

        return Yii::$app->services->pay->$action($this, $baseOrder);
    }

    /**
     * 获取支付基础信息
     *
     * @param $type
     * @param $data
     * @return array
     */
    protected function getBaseOrderInfo()
    {
        $data = Json::decode($this->data);
        switch ($this->orderGroup) {
            case PayEnum::ORDER_GROUP :
                // TODO 查询订单获取订单信息
                $orderSn = '';
                $totalFee = '';
                $order = [
                    'body' => '',
                    'total_fee' => $totalFee,
                ];
                break;
            case PayEnum::ORDER_GROUP_GOODS :
                // TODO 查询充值生成充值订单
                $orderSn = '';
                $totalFee = '';
                $order = [
                    'body' => '',
                    'total_fee' => $totalFee,
                ];
                break;
        }

        $order['out_trade_no'] = Yii::$app->services->pay->getOutTradeNo($totalFee, $orderSn, $this->payType,
            $this->tradeType, $this->orderGroup);

        // 必须返回 body、total_fee、out_trade_no
        return $order;
    }
}