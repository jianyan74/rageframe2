<?php

namespace addons\Wechat\services;

use common\components\Service;

/**
 * Class Application
 *
 * @package addons\Wechat\services
 * @property \addons\Wechat\services\SettingService $setting 参数设置
 * @property \addons\Wechat\services\FansService $fans 粉丝
 * @property \addons\Wechat\services\FansTagsService $fansTags 粉丝标签
 * @property \addons\Wechat\services\FansTagMapService $fansTagMap 粉丝标签关联
 * @property \addons\Wechat\services\FansStatService $fansStat 粉丝统计
 * @property \addons\Wechat\services\AttachmentService $attachment 资源
 * @property \addons\Wechat\services\AttachmentNewsService $attachmentNews 资源图文
 * @property \addons\Wechat\services\MenuService $menu 菜单
 * @property \addons\Wechat\services\MenuProvincesService $menuProvinces 个性菜单地区
 * @property \addons\Wechat\services\MessageService $message 微信消息
 * @property \addons\Wechat\services\MsgHistoryService $msgHistory 历史消息
 * @property \addons\Wechat\services\QrcodeService $qrcode 二维码
 * @property \addons\Wechat\services\QrcodeStatService $qrcodeStat 二维码统计
 * @property \addons\Wechat\services\RuleService $rule 规则
 * @property \addons\Wechat\services\RuleStatService $ruleStat 规则统计
 * @property \addons\Wechat\services\RuleKeywordService $ruleKeyword 规则关键字
 * @property \addons\Wechat\services\RuleKeywordStatService $ruleKeywordStat 规则关键字统计
 * @property \addons\Wechat\services\ReplyDefaultService $replyDefault 默认回复
 *
 * @author jianyan74 <751393839@qq.com>
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'setting' => 'addons\Wechat\services\SettingService',
        'fans' => 'addons\Wechat\services\FansService',
        'fansTags' => 'addons\Wechat\services\FansTagsService',
        'fansTagMap' => 'addons\Wechat\services\FansTagMapService',
        'fansStat' => 'addons\Wechat\services\FansStatService',
        'attachment' => 'addons\Wechat\services\AttachmentService',
        'attachmentNews' => 'addons\Wechat\services\AttachmentNewsService',
        'menu' => 'addons\Wechat\services\MenuService',
        'menuProvinces' => 'addons\Wechat\services\MenuProvincesService',
        'qrcode' => 'addons\Wechat\services\QrcodeService',
        'qrcodeStat' => 'addons\Wechat\services\QrcodeStatService',
        'message' => 'addons\Wechat\services\MessageService',
        'msgHistory' => 'addons\Wechat\services\MsgHistoryService',
        'rule' => 'addons\Wechat\services\RuleService',
        'ruleStat' => 'addons\Wechat\services\RuleStatService',
        'ruleKeyword' => 'addons\Wechat\services\RuleKeywordService',
        'ruleKeywordStat' => 'addons\Wechat\services\RuleKeywordStatService',
        'replyDefault' => 'addons\Wechat\services\ReplyDefaultService',
        'templateMsg' => [
            'class' => 'addons\Wechat\services\TemplateMsgService',
            'queueSwitch' => true, // 是否丢进队列
        ],
    ];
}