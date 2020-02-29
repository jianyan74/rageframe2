<?php

namespace addons\Wechat\services;

use Yii;
use common\helpers\ArrayHelper;
use common\enums\WechatEnum;
use common\components\Service;
use addons\Wechat\common\models\QrcodeStat;

/**
 * Class QrcodeStatService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class QrcodeStatService extends Service
{
    /**
     * 判断二维码扫描事件
     *
     * @param array $message 微信消息
     * @return bool|mixed
     */
    public function scan($message)
    {
        // 关注事件
        if ($message['Event'] == WechatEnum::EVENT_SUBSCRIBE && !empty($message['Ticket'])) {
            if ($qrCode = Yii::$app->wechatService->qrcode->findByWhere(['ticket' => trim($message['Ticket'])])) {
                $this->create($qrCode, $message['FromUserName'], QrcodeStat::TYPE_ATTENTION);

                return $qrCode['keyword'];
            }
        }

        if (!isset($message['EventKey'])) {
            return false;
        }

        // 扫描事件
        $where = ['scene_str' => $message['EventKey']];
        if (is_numeric($message['EventKey'])) {
            $where = ['scene_id' => $message['EventKey']];
        }

        if ($qrCode = Yii::$app->wechatService->qrcode->findByWhere($where)) {
            $this->create($qrCode, $message['FromUserName'], QrcodeStat::TYPE_SCAN);

            return $qrCode['keyword'];
        }

        return false;
    }

    /**
     * 插入扫描记录
     *
     * @param $qrCode
     * @param $openid
     * @param $type
     */
    public function create($qrCode, $openid, $type)
    {
        $model = new QrcodeStat();
        $model->attributes = ArrayHelper::toArray($qrCode);
        $model->openid = $openid;
        $model->type = $type;
        $model->save();
    }
}