<?php

namespace common\enums;

/**
 * Class WechatEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WechatEnum
{
    /**
     * 普通消息
     */
    const TYPE_TEXT = "text";// 文本消息
    const TYPE_IMAGE = "image";// 图片消息
    const TYPE_VOICE = "voice";// 语音消息
    const TYPE_VIDEO = "video";// 视频消息
    const TYPE_LOCATION = "location";// 地理位置消息
    const TYPE_LINK = "link";// 链接消息
    const TYPE_EVENT = "event";// 事件

    /**
     * 事件
     */
    const EVENT_SUBSCRIBE = "subscribe"; // 关注事件
    const EVENT_UN_SUBSCRIBE = "unsubscribe";// 取消关注事件
    const EVENT_LOCATION = "LOCATION";// 上传地址事件
    const EVENT_VIEW = "VIEW";// 访问链接事件
    const EVENT_CILCK = "CLICK";// 点击事件
    const EVENT_SCAN = "SCAN";// 二维码扫描事件

    /**
     * 其他消息
     */
    const TYPE_SHORTVIDEO = "shortvideo";// 小视频消息
    const TYPE_TRACE = "trace";// 上报地理位置
    const TYPE_MERCHANT_ORDER = "merchant_order";// 微小店消息
    const TYPE_SHAKEAROUND_USER_SHAKE = "ShakearoundUserShake";// 摇一摇:开始摇一摇消息
    const TYPE_SHAKEAROUND_LOTTERY_BIND = "ShakearoundLotteryBind";// 摇一摇:摇到了红包消息
    const TYPE_WIFI_CONNECTED = "WifiConnected";// Wifi连接成功消息

    /**
     * 特殊消息类型
     *
     * @var array
     */
    public static $typeExplanation = [
        self::TYPE_IMAGE => "图片消息",
        self::TYPE_VOICE => "语音消息",
        self::TYPE_VIDEO => "视频消息",
        self::TYPE_SHORTVIDEO => "小视频消息",
        self::TYPE_LOCATION => "位置消息",
        self::TYPE_TRACE => "上报地理位置",
        self::TYPE_LINK => "链接消息",
        self::TYPE_MERCHANT_ORDER => "微小店消息",
        self::TYPE_SHAKEAROUND_USER_SHAKE => "摇一摇：开始摇一摇消息",
        self::TYPE_SHAKEAROUND_LOTTERY_BIND => "摇一摇：摇到了红包消息",
        self::TYPE_WIFI_CONNECTED => "wifi连接成功消息",
    ];

    // 发送消息
    const SEND_TYPE_TEXT = 'text';
}