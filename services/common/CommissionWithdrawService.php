<?php

namespace services\common;

use Yii;
use common\components\Service;
use common\enums\PayTypeEnum;
use common\enums\StatusEnum;
use common\models\forms\CommissionWithdrawForm;
use common\models\common\CommissionWithdrawLog;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CommissionWithdrawService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class CommissionWithdrawService extends Service
{
    /**
     * 提现到零钱
     *
     * @param CommissionWithdrawForm $commissionWithdraw
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatToBalance(CommissionWithdrawForm $commissionWithdraw)
    {
        $result = Yii::$app->wechat->payment->transfer->toBalance([
            'partner_trade_no' => $commissionWithdraw->withdraw_no, // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
            'openid' => $commissionWithdraw->enc_bank_no,
            'check_name' => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
            're_user_name' => $commissionWithdraw->enc_true_name, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => $commissionWithdraw->cash * 100, // 企业付款金额，单位为分
            'desc' => $commissionWithdraw->memo, // 企业付款操作说明信息。必填
        ]);

        if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            throw new UnprocessableEntityHttpException($result['err_code_des']);
        }

        // 记录日志
        $log = $this->initLog($commissionWithdraw);
        $log->mch_id = $result['mch_id'];
        $log->pay_type = PayTypeEnum::WECHAT;
        $log->transaction_id = $result['payment_no'];
        $log->trade_type = 'balance';
        $log->save();

        return $result;
    }

    /**
     * 提现到银行卡
     *
     * @param CommissionWithdrawForm $commissionWithdraw
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatToBankCard(CommissionWithdrawForm $commissionWithdraw)
    {
        $bankNumber = Yii::$app->services->bankNumber->findByBankName($commissionWithdraw->enc_bank_name);
        if (!$bankNumber) {
            throw new UnprocessableEntityHttpException('该银行暂时不支持提现');
        }

        $result = Yii::$app->wechat->payment->transfer->toBankCard([
            'partner_trade_no' => $commissionWithdraw->withdraw_no,
            'enc_bank_no' => $commissionWithdraw->enc_bank_no, // 银行卡号
            'enc_true_name' => $commissionWithdraw->enc_true_name,   // 银行卡对应的用户真实姓名
            'bank_code' => $bankNumber->bank_number, // 银行编号
            'amount' => $commissionWithdraw->cash * 100, // 企业付款金额，单位为分
            'desc' => $commissionWithdraw->memo, // 企业付款操作说明信息。必填
        ]);

        if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            throw new UnprocessableEntityHttpException($result['err_code_des']);
        }

        // 记录日志
        $log = $this->initLog($commissionWithdraw);
        $log->mch_id = $result['mch_id'];
        $log->pay_type = PayTypeEnum::WECHAT;
        $log->transaction_id = $result['payment_no'];
        $log->trade_type = 'bankCard';
        $log->save();

        return $result;
    }

    /**
     * 支付宝单次转账
     *
     * @param CommissionWithdrawForm $commissionWithdraw
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \yii\base\InvalidConfigException
     */
    public function alipayToAccount(CommissionWithdrawForm $commissionWithdraw)
    {
        $result = Yii::$app->pay->alipay->transfer([
            'out_biz_no' => $commissionWithdraw->withdraw_no,
            'payee_account' => $commissionWithdraw->enc_bank_no,
            'amount' => $commissionWithdraw->cash,
            'payee_real_name' => $commissionWithdraw->enc_true_name, // 非必填
            'remark' => $commissionWithdraw->memo, // 非必填
        ]);

        if ($result['code'] != '10000') {
            if (isset($result['sub_msg'])) {
                throw new UnprocessableEntityHttpException($result['sub_msg']);
            }

            throw new UnprocessableEntityHttpException($result['msg']);
        }

        // 记录日志
        $log = $this->initLog($commissionWithdraw);
        $log->pay_type = PayTypeEnum::ALI;
        $log->transaction_id = $result['order_id'];
        $log->trade_type = 'account';
        $log->save();

        return $result;
    }

    /**
     * @param CommissionWithdrawForm $commissionWithdraw
     * @return CommissionWithdrawLog
     */
    protected function initLog(CommissionWithdrawForm $commissionWithdraw)
    {
        $log = new CommissionWithdrawLog();
        $log = $log->loadDefaultValues();
        $log->addons_name = Yii::$app->params['addon']['name'] ?? '';
        $log->app_id = Yii::$app->id;
        $log->create_ip = Yii::$app->request->userIP;
        $log->withdraw_no = $commissionWithdraw->withdraw_no;
        $log->enc_bank_name = $commissionWithdraw->enc_bank_name;
        $log->enc_bank_no = $commissionWithdraw->enc_bank_no;
        $log->enc_true_name = $commissionWithdraw->enc_true_name;
        $log->body = $commissionWithdraw->memo;
        $log->total_fee = $commissionWithdraw->cash;
        $log->pay_fee = $commissionWithdraw->cash;
        $log->notify_url = $commissionWithdraw->notify_url;
        $log->pay_ip = Yii::$app->request->userIP;
        $log->pay_status = StatusEnum::ENABLED;
        $log->pay_time = time();

        return $log;
    }

    /**
     * @var string[]
     */
    protected $wechatStatus = [
        'SUCCESS' => '转账成功',
        'FAILED' => '转账失败',
        'PROCESSING' => '处理中',
    ];

    /**
     * @param $withdraw_no
     * @param bool $allReturn
     * @return mixed
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function queryByWithdrawNo($withdraw_no, $allReturn = false)
    {
        $log = $this->findByWithdrawNo($withdraw_no);
        if (!$log) {
            throw new NotFoundHttpException('找不到转账记录');
        }

        switch ($log->pay_type) {
            case PayTypeEnum::ALI :
                $result = Yii::$app->pay->alipay->transferQuery([
                    'out_biz_no' => $withdraw_no,
                    'order_id' => $log->transaction_id,
                ]);

                if ($result['code'] != '10000') {
                    if (isset($result['sub_msg'])) {
                        throw new UnprocessableEntityHttpException($result['sub_msg']);
                    }

                    throw new UnprocessableEntityHttpException($result['msg']);
                }

                return $allReturn ? $result : $result['msg'];

                break;
            case PayTypeEnum::WECHAT :
                if ($log->trade_type == 'balance') {
                    $result = Yii::$app->wechat->payment->transfer->queryBalanceOrder($withdraw_no);
                } else {
                    $result = Yii::$app->wechat->payment->transfer->queryBankCardOrder($withdraw_no);
                }

                if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                    throw new UnprocessableEntityHttpException($result['reason']);
                }

                return $this->wechatStatus[$result['status']];

                break;
        }

        throw new UnprocessableEntityHttpException('无效的记录');
    }

    /**
     * @param $withdraw_no
     * @return array|\yii\db\ActiveRecord|null|CommissionWithdrawLog
     */
    public function findByWithdrawNo($withdraw_no)
    {
        return CommissionWithdrawLog::find()
            ->where(['withdraw_no' => $withdraw_no])
            ->one();
    }
}