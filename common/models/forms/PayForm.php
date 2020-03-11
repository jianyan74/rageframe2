<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use common\enums\PayGroupEnum;
use common\enums\PayTypeEnum;
use common\models\common\PayLog;
use common\interfaces\PayHandler;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;

/**
 * 支付校验
 *
 * Class PayForm
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class PayForm extends PayLog
{
    public $data;

    /**
     * 授权码
     *
     * @var
     */
    public $code;

    /**
     * @var
     */
    private $_handlers;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['order_group', 'pay_type', 'data', 'trade_type', 'member_id'], 'required'],
            [['order_group'], 'in', 'range' => PayGroupEnum::getKeys()],
            [['pay_type'], 'in', 'range' => PayTypeEnum::getKeys()],
            [['notify_url', 'return_url', 'code', 'openid'], 'string'],
            [['data'], 'safe'],
            [['trade_type'], 'verifyTradeType'],
        ];
    }

    /**
     * 校验交易类型
     *
     * @param $attribute
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verifyTradeType($attribute)
    {
        try {
            $this->data = Json::decode($this->data);
        } catch (\Exception $e) {
            $this->addError($attribute, $e->getMessage());

            return;
        }

        switch ($this->pay_type) {
            case PayTypeEnum::WECHAT :
                if (!in_array($this->trade_type, ['native', 'app', 'js', 'pos', 'mweb', 'mini_program'])) {
                    $this->addError($attribute, '微信交易类型不符');

                    return;
                }

                // 直接通过授权码进行支付
                if ($this->code) {
                    if ($this->trade_type == 'mini_program') {
                        $auth = Yii::$app->wechat->miniProgram->auth->session($this->code);
                        Yii::$app->debris->getWechatError($auth);
                        $this->openid = $auth['openid'];
                    }

                    if ($this->trade_type == 'js') {
                        $user = Yii::$app->wechat->app->oauth->user();
                        $this->openid = $user['openid'];
                    }
                }

                break;
            case PayTypeEnum::ALI :
                if (!in_array($this->trade_type, ['pc', 'app', 'f2f', 'wap'])) {
                    $this->addError($attribute, '支付宝交易类型不符');
                }
                break;
            case PayTypeEnum::UNION :
                if (!in_array($this->trade_type, ['app', 'html'])) {
                    $this->addError($attribute, '银联交易类型不符');
                }
                break;
        }


    }

    /**
     * 执行类
     *
     * @param array $handlers
     */
    public function setHandlers(array $handlers)
    {
        $this->_handlers = $handlers;
    }

    /**
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    public function getConfig()
    {
        if (!isset($this->_handlers[$this->order_group])) {
            throw new UnprocessableEntityHttpException('找不到订单组别');
        }

        /** @var Model|PayHandler $model */
        $model = new $this->_handlers[$this->order_group]();
        if (!($model instanceof PayHandler)) {
            throw new UnprocessableEntityHttpException('无效的订单组别');
        }

        $model->attributes = $this->data;
        if (!$model->validate()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        $log = new PayLog();
        $log->out_trade_no = StringHelper::randomNum(date('YmdHis'), 8);
        if ($model->isQueryOrderSn() == true && ($history = Yii::$app->services->pay->findByOrderSn($model->getOrderSn()))) {
            $log = $history;
        }

        $log->attributes = ArrayHelper::toArray($this);
        $log->body = $model->getBody();
        $log->detail = $model->getDetails();
        $log->order_sn = $model->getOrderSn();
        $log->total_fee = $model->getTotalFee();
        $log->pay_fee = $log->total_fee;
        if (!$log->save()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($log->getFirstErrors()));
        }

        return $this->payConfig($log);
    }

    /**
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    protected function payConfig(PayLog $log)
    {
        switch ($log->pay_type) {
            case PayTypeEnum::WECHAT :
                return Yii::$app->services->pay->wechat($log);
                break;
            case PayTypeEnum::ALI :
                return Yii::$app->services->pay->alipay($log);
                break;
            case PayTypeEnum::UNION :
                return Yii::$app->services->pay->union($log);
                break;
        }
    }
}