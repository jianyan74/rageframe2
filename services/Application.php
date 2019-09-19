<?php

namespace services;

use common\components\Service;

/**
 * Class Application
 *
 * @package services
 * @property \services\merchant\MerchantService $merchant 商户
 * @property \services\sys\SysService $sys 系统
 * @property \services\sys\ManagerService $sysManager 管理员
 * @property \services\sys\MenuService $sysMenu 菜单
 * @property \services\sys\MenuCateService $sysMenuCate 菜单分类
 * @property \services\sys\NotifyService $sysNotify 消息
 * @property \services\sys\NotifyPullTimeService $sysNotifyPullTime 消息拉取日志
 * @property \services\sys\NotifySubscriptionConfigService $sysNotifySubscriptionConfig 提醒配置
 * @property \services\api\AccessTokenService $apiAccessToken Api授权key
 * @property \services\wechat\SettingService $wechatSetting 参数设置
 * @property \services\wechat\FansService $wechatFans 粉丝
 * @property \services\wechat\FansTagsService $wechatFansTags 粉丝标签
 * @property \services\wechat\FansTagMapService $wechatFansTagMap 粉丝标签关联
 * @property \services\wechat\FansStatService $wechatFansStat 粉丝统计
 * @property \services\wechat\AttachmentService $wechatAttachment 资源
 * @property \services\wechat\AttachmentNewsService $wechatAttachmentNews 资源图文
 * @property \services\wechat\MenuService $wechatMenu 菜单
 * @property \services\wechat\MenuProvincesService $wechatMenuProvinces 个性菜单地区
 * @property \services\wechat\MessageService $wechatMessage 微信消息
 * @property \services\wechat\MsgHistoryService $wechatMsgHistory 历史消息
 * @property \services\wechat\QrcodeService $wechatQrcode 二维码
 * @property \services\wechat\QrcodeStatService $wechatQrcodeStat 二维码统计
 * @property \services\wechat\RuleService $wechatRule 规则
 * @property \services\wechat\RuleStatService $wechatRuleStat 规则统计
 * @property \services\wechat\RuleKeywordService $wechatRuleKeyword 规则关键字
 * @property \services\wechat\RuleKeywordStatService $wechatRuleKeywordStat 规则关键字统计
 * @property \services\wechat\ReplyDefaultService $wechatReplyDefault 默认回复
 * @property \services\member\MemberService $member 会员
 * @property \services\member\AuthService $memberAuth 会员第三方授权
 * @property \services\member\AccountService $memberAccount 会员账号
 * @property \services\member\AddressService $memberAddress 会员收货地址
 * @property \services\member\CreditsLogService $memberCreditsLog 会员积分/余额变动日志
 * @property \services\common\ActionLogService $actionLog 行为日志
 * @property \services\common\ActionBehaviorService $actionBehavior 可被记录的行为
 * @property \services\common\AttachmentService $attachment 公用资源
 * @property \services\common\LogService $log 公用日志
 * @property \services\common\PayService $pay 公用支付
 * @property \services\common\MailerService $mailer 公用邮件
 * @property \services\common\SmsService $sms 公用短信
 * @property \services\common\AddonsService $addons 插件
 * @property \services\common\AddonsConfigService $addonsConfig 插件配置
 * @property \services\common\AddonsBindingService $addonsBinding 插件菜单入口
 * @property \services\common\AuthItemService $authItem 权限
 * @property \services\common\AuthRoleService $authRole 角色
 * @property \services\common\AuthAssignmentService $authAssignment 授权
 * @property \services\common\AuthService $auth 权限验证
 * @property \services\common\ConfigService $config 基础配置
 * @property \services\common\ConfigCateService $configCate 基础配置分类
 * @property \services\common\ProvincesService $provinces ip黑名单
 * @property \services\common\IpBlacklistService $ipBlacklist 省市区
 * @property \services\oauth2\ServerService $oauth2Server oauth2服务
 * @property \services\oauth2\ClientService $oauth2Client oauth2客户端
 * @property \services\oauth2\AccessTokenService $oauth2AccessToken oauth2授权token
 * @property \services\oauth2\RefreshTokenService $oauth2RefreshToken oauth2刷新token
 * @property \services\oauth2\AuthorizationCodeService $oauth2AuthorizationCode oauth临时code
 *
 * @author jianyan74 <751393839@qq.com>
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'merchant' => [
            'class' => 'services\merchant\MerchantService',
        ],
        'apiAccessToken' => [
            'class' => 'services\api\AccessTokenService',
            'cache' => false, // 启用缓存到缓存读取用户信息
            'timeout' => 720, // 缓存过期时间，单位秒
        ],
        /** ------ 系统 ------ **/
        'sys' => 'services\sys\SysService',
        'sysMenu' => 'services\sys\MenuService',
        'sysMenuCate' => 'services\sys\MenuCateService',
        'sysNotify' => 'services\sys\NotifyService',
        'sysNotifyPullTime' => 'services\sys\NotifyPullTimeService',
        'sysNotifySubscriptionConfig' => 'services\sys\NotifySubscriptionConfigService',
        'sysManager' => 'services\sys\ManagerService',
        /** ------ 微信 ------ **/
        'wechatSetting' => 'services\wechat\SettingService',
        'wechatFans' => 'services\wechat\FansService',
        'wechatFansTags' => 'services\wechat\FansTagsService',
        'wechatFansTagMap' => 'services\wechat\FansTagMapService',
        'wechatFansStat' => 'services\wechat\FansStatService',
        'wechatAttachment' => 'services\wechat\AttachmentService',
        'wechatAttachmentNews' => 'services\wechat\AttachmentNewsService',
        'wechatMenu' => 'services\wechat\MenuService',
        'wechatMenuProvinces' => 'services\wechat\MenuProvincesService',
        'wechatQrcode' => 'services\wechat\QrcodeService',
        'wechatQrcodeStat' => 'services\wechat\QrcodeStatService',
        'wechatMessage' => 'services\wechat\MessageService',
        'wechatMsgHistory' => 'services\wechat\MsgHistoryService',
        'wechatRule' => 'services\wechat\RuleService',
        'wechatRuleStat' => 'services\wechat\RuleStatService',
        'wechatRuleKeyword' => 'services\wechat\RuleKeywordService',
        'wechatRuleKeywordStat' => 'services\wechat\RuleKeywordStatService',
        'wechatReplyDefault' => 'services\wechat\ReplyDefaultService',
        'wechatTemplateMsg' => [
            'class' => 'services\wechat\TemplateMsgService',
            'queueSwitch' => true, // 是否丢进队列
        ],
        /** ------ 用户 ------ **/
        'member' => 'services\member\MemberService',
        'memberAuth' => 'services\member\AuthService',
        'memberAccount' => 'services\member\AccountService',
        'memberAddress' => 'services\member\AddressService',
        'memberCreditsLog' => 'services\member\CreditsLogService',
        /** ------ 公用部分 ------ **/
        'config' => 'services\common\ConfigService',
        'configCate' => 'services\common\ConfigCateService',
        'actionLog' => 'services\common\ActionLogService',
        'actionBehavior' => 'services\common\ActionBehaviorService',
        'ipBlacklist' => 'services\common\IpBlacklistService',
        'provinces' => 'services\common\ProvincesService',
        'attachment' => 'services\common\AttachmentService',
        'addons' => 'services\common\AddonsService',
        'addonsConfig' => 'services\common\AddonsConfigService',
        'addonsBinding' => 'services\common\AddonsBindingService',
        'auth' => 'services\common\AuthService',
        'authItem' => 'services\common\AuthItemService',
        'authRole' => 'services\common\AuthRoleService',
        'authAssignment' => 'services\common\AuthAssignmentService',
        'log' => [
            'class' => 'services\common\LogService',
            'queueSwitch' => false, // 是否丢进队列
            'exceptCode' => [403] // 除了数组内的状态码不记录，其他按照配置记录
        ],
        'pay' => 'services\common\PayService',
        'jPush' => 'services\common\JPushService',
        'sms' => [
            'class' => 'services\common\SmsService',
            'queueSwitch' => false, // 是否丢进队列
        ],
        'mailer' => [
            'class' => 'services\common\MailerService',
            'queueSwitch' => false, // 是否丢进队列
        ],
        /** ------ oauth2 ------ **/
        'oauth2Server' => 'services\oauth2\ServerService',
        'oauth2Client' => 'services\oauth2\ClientService',
        'oauth2AccessToken' => 'services\oauth2\AccessTokenService',
        'oauth2RefreshToken' => 'services\oauth2\RefreshTokenService',
        'oauth2AuthorizationCode' => 'services\oauth2\AuthorizationCodeService',
    ];
}