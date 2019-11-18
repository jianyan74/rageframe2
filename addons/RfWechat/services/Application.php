<?php

namespace addons\RfWechat\services;

use common\components\Service;

/**
 * Class Application
 *
 * @package addons\RfWechat\services
 * @property \addons\RfWechat\services\SettingService $setting 参数设置
 * @property \addons\RfWechat\services\FansService $fans 粉丝
 * @property \addons\RfWechat\services\FansTagsService $fansTags 粉丝标签
 * @property \addons\RfWechat\services\FansTagMapService $fansTagMap 粉丝标签关联
 * @property \addons\RfWechat\services\FansStatService $fansStat 粉丝统计
 * @property \addons\RfWechat\services\AttachmentService $attachment 资源
 * @property \addons\RfWechat\services\AttachmentNewsService $attachmentNews 资源图文
 * @property \addons\RfWechat\services\MenuService $menu 菜单
 * @property \addons\RfWechat\services\MenuProvincesService $menuProvinces 个性菜单地区
 * @property \addons\RfWechat\services\MessageService $message 微信消息
 * @property \addons\RfWechat\services\MsgHistoryService $msgHistory 历史消息
 * @property \addons\RfWechat\services\QrcodeService $qrcode 二维码
 * @property \addons\RfWechat\services\QrcodeStatService $qrcodeStat 二维码统计
 * @property \addons\RfWechat\services\RuleService $rule 规则
 * @property \addons\RfWechat\services\RuleStatService $ruleStat 规则统计
 * @property \addons\RfWechat\services\RuleKeywordService $ruleKeyword 规则关键字
 * @property \addons\RfWechat\services\RuleKeywordStatService $ruleKeywordStat 规则关键字统计
 * @property \addons\RfWechat\services\ReplyDefaultService $replyDefault 默认回复
 *
 * @author jianyan74 <751393839@qq.com>
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'setting' => 'addons\RfWechat\services\SettingService',
        'fans' => 'addons\RfWechat\services\FansService',
        'fansTags' => 'addons\RfWechat\services\FansTagsService',
        'fansTagMap' => 'addons\RfWechat\services\FansTagMapService',
        'fansStat' => 'addons\RfWechat\services\FansStatService',
        'attachment' => 'addons\RfWechat\services\AttachmentService',
        'attachmentNews' => 'addons\RfWechat\services\AttachmentNewsService',
        'menu' => 'addons\RfWechat\services\MenuService',
        'menuProvinces' => 'addons\RfWechat\services\MenuProvincesService',
        'qrcode' => 'addons\RfWechat\services\QrcodeService',
        'qrcodeStat' => 'addons\RfWechat\services\QrcodeStatService',
        'message' => 'addons\RfWechat\services\MessageService',
        'msgHistory' => 'addons\RfWechat\services\MsgHistoryService',
        'rule' => 'addons\RfWechat\services\RuleService',
        'ruleStat' => 'addons\RfWechat\services\RuleStatService',
        'ruleKeyword' => 'addons\RfWechat\services\RuleKeywordService',
        'ruleKeywordStat' => 'addons\RfWechat\services\RuleKeywordStatService',
        'replyDefault' => 'addons\RfWechat\services\ReplyDefaultService',
        'templateMsg' => [
            'class' => 'addons\RfWechat\services\TemplateMsgService',
            'queueSwitch' => true, // 是否丢进队列
        ],
    ];
}