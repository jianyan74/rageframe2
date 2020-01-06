<?php

namespace addons\Wechat\services;

use Yii;
use yii\helpers\Json;
use common\enums\WechatEnum;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\Html;
use addons\Wechat\common\models\MsgHistory;

/**
 * Class MsgHistoryService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class MsgHistoryService extends Service
{
    protected $model;

    /**
     * 写入历史记录
     *
     * @param array $data 历史消息记录
     * @param array $message 微信消息
     */
    public function save($data, $message)
    {
        $setting = Yii::$app->wechatService->setting->getByFieldName('history');
        // 记录历史
        if (!isset($setting['history_status']) || $setting['history_status'] == StatusEnum::ENABLED) {
            $msgHistory = new MsgHistory();
            $data['message'] = $data['type'] == WechatEnum::TYPE_TEXT ? $message['Content'] : Json::encode($message);
            $msgHistory->attributes = $data;
            $msgHistory->save();
        }

        // 统计记录
        if (!isset($setting['utilization_status']) || $setting['utilization_status'] == StatusEnum::ENABLED) {
            // 插入规则统计
            !empty($data['rule_id']) && Yii::$app->wechatService->ruleStat->set($data['rule_id']);

            // 插入关键字统计
            if (!empty($data['keyword_id']) && !empty($data['rule_id'])) {
                Yii::$app->wechatService->ruleKeywordStat->set($data['rule_id'], $data['keyword_id']);
            }
        }

        return;
    }

    /**
     * 解析微信发过来的消息内容
     *
     * @param $type
     * @param $messgae
     * @return mixed|string
     */
    public function readMessage($type, $messgae)
    {
        switch ($type) {
            case WechatEnum::TYPE_TEXT :
                return Html::encode($messgae);
                break;
            case WechatEnum::TYPE_IMAGE :
                $messgae = Json::decode($messgae);
                return $messgae['PicUrl'];
                break;
            case WechatEnum::TYPE_VIDEO :
                $messgae = Json::decode($messgae);
                return "MediaId【" . $messgae['MediaId'] . "】";
                break;
            case WechatEnum::TYPE_LOCATION :
                $messgae = Json::decode($messgae);
                return '主动发送位置 : 经纬度【' . $messgae['Location_X'] . ',' . $messgae['Location_Y'] . "】<br>地址 : " . $messgae['Label'];
                break;
            case WechatEnum::EVENT_CILCK :
                $messgae = Json::decode($messgae);
                return '单击菜单触发 : ' . $messgae['EventKey'];
                break;
            case WechatEnum::EVENT_SUBSCRIBE :
                return '关注公众号';
                break;
            case WechatEnum::TYPE_VOICE :
                $messgae = Json::decode($messgae);
                return isset($messgae['Recognition']) ? $messgae['Recognition'] : '语音消息';
                break;
            // 触发事件
            case WechatEnum::TYPE_EVENT :

                $messgae = Json::decode($messgae);
                switch ($messgae['Event']) {
                    case WechatEnum::EVENT_UN_SUBSCRIBE :
                        return '取消关注公众号';
                        break;
                    case WechatEnum::EVENT_SUBSCRIBE :
                        return '通过二维码关注公众号 : ' . str_replace('qrscene_', '', $messgae['EventKey']);
                        break;
                    case WechatEnum::EVENT_LOCATION :
                        return '被动发送位置 : 经纬度【' . $messgae['Latitude'] . ',' . $messgae['Longitude'] . "】精度:" . $messgae['Precision'];
                        break;
                    case WechatEnum::EVENT_VIEW :
                        return "单击菜单访问 : " . $messgae['EventKey'];
                        break;
                    case WechatEnum::EVENT_CILCK :
                        return "单击菜单触发关键字 : " . $messgae['EventKey'];
                        break;
                    case WechatEnum::EVENT_SCAN :
                        return "二维码扫描 : " . $messgae['EventKey'];
                        break;
                    case 'location_select' :
                        $sendLocationInfo = $messgae['SendLocationInfo'];
                        return "主动发送位置 : " . '经纬度【' . $sendLocationInfo['Location_X'] . ',' . $sendLocationInfo['Location_Y'] . "】地址:" . $sendLocationInfo['Label'];
                        break;
                    case 'scancode_waitmsg' :
                        $scanCodeInfo = $messgae['ScanCodeInfo'];
                        return "调用二维码扫描等待返回地址 : " . $scanCodeInfo['ScanResult'];
                        break;
                    case 'pic_sysphoto' :
                        return "调用拍照发图";
                        break;
                    case 'pic_photo_or_album' :
                        return "调用拍照相册";
                        break;
                    case 'scancode_push' :
                        $scanCodeInfo = $messgae['ScanCodeInfo'];
                        return "调用二维码直接扫描返回地址 : " . $scanCodeInfo['ScanResult'];
                        break;
                    case 'MASSSENDJOBFINISH' :
                        return "点击图文MsgID为 : " . $messgae['MsgID'];
                        break;
                    default :
                        return Json::encode($messgae);
                        break;
                }
                break;
            default :
                return $messgae;
                break;
        }
    }
}