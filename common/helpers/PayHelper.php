<?php
namespace common\helpers;

use common\enums\StatusEnum;
use common\models\common\PayLog;

/**
 * 支付日志辅助类
 *
 * Class PayHelper
 * @package common\helpers
 */
class PayHelper
{
    /**
     * 获取订单支付日志编号
     *
     * @param int $payFee 单位分
     * @param string $orderSn 关联订单号
     * @param int $orderGroup 订单组别 如果有自己的多种订单类型请去\common\models\common\PayLog里面增加对应的常量
     * @param int $payType 支付类型 1:微信;2:支付宝;3:银联;4:微信小程序
     * @return string
     */
    public static function getOutTradeNo($totalFee, string $orderSn, int $orderGroup, int $payType, $tradeType)
    {
        $payModel = new PayLog();
        $payModel->out_trade_no = time() . StringHelper::randomNum();
        $payModel->total_fee = $totalFee;
        $payModel->order_sn = $orderSn;
        $payModel->order_group = $orderGroup;
        $payModel->pay_type = $payType;
        $payModel->trade_type = $tradeType;
        $payModel->save();

        return $payModel->out_trade_no;
    }

    /**
     * 获取订单编号
     *
     * @param string $outTradeNo 订单号
     * @param array $data 回调数据 字段['openid', 'mch_id', 'total_fee', 'transaction_id', 'fee_type', 'trade_type', 'pay_fee']
     * @return bool|string|array
     */
    public static function notify($outTradeNo, array $data)
    {
        // 找不到数据
        if (!($model = PayLog::findOne(['out_trade_no' => $outTradeNo])))
        {
            return false;
        }

        // 支付成功
        if ($model->pay_status == StatusEnum::ENABLED)
        {
            return false;
        }

        $model->attributes = $data;
        $model->pay_status = StatusEnum::ENABLED;
        if ($model->save())
        {
            return $model;
        }

        return false;
    }

    /**
     * 告诉微信已经成功了
     *
     * @return bool|string
     */
    public static function notifyWechatSuccess()
    {
        return ArrayHelper::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
    }

    /**
     * 告诉微信失败了
     *
     * @return bool|string
     */
    public static function notifyWechatFail()
    {
        return ArrayHelper::toXml(['return_code' => 'FAIL', 'return_msg' => 'OK']);
    }
}