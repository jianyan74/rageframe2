<?php

namespace addons\RfExample;

/**
 * Class Addon
 * @package addons\RfExample
 */
class AddonConfig
{
    /**
     * 配置信息
     *
     * @var array
     */
    public $info = [
        'name' => 'RfExample',
        'title' => '功能案例',
        'brief_introduction' => '系统的一些功能案例',
        'description' => '系统自带的功能使用示例及其说明，包含一些简单的交互',
        'author' => '简言',
        'version' => '1.0.0',
    ];

    /**
     * 应用配置
     *
     * @var array
     */
    public $appsConfig = [
        'backend' => 'common/config/backend.php',
        'frontend' => 'common/config/frontend.php',
        'wechat' => 'common/config/wechat.php',
        'api' => 'common/config/api.php',
    ];

    /**
     * 参数配置
     *
     * @var bool
     */
    public $isSetting = true;

    /**
     * 钩子
     *
     * @var bool
     */
    public $isHook = true;

    /**
     * 规则管理
     *
     * @var bool
     */
    public $isRule = true;

    /**
     * 类别
     *
     * @var string
     * [
     *      'plug'      => "功能插件",
     *      'business'  => "主要业务",
     *      'customer'  => "客户关系",
     *      'activity'  => "营销及活动",
     *      'services'  => "常用服务及工具",
     *      'biz'       => "行业解决方案",
     *      'h5game'    => "H5游戏",
     *      'other'     => "其他",
     * ]
     */
    public $group = 'services';

    /**
     * 微信接收消息类别
     *
     * @var array
     * 例如 : ['image','voice','video','shortvideo']
     */
    public $wechatMessage = ["image", "voice", "video", "shortvideo", "location", "trace", "link", "merchant_order", "ShakearoundUserShake", "ShakearoundLotteryBind", "WifiConnected"];

    /**
     * 保存在当前模块的根目录下面
     *
     * 例如 public $install = 'Install';
     * 安装SQL,只支持php文件
     * @var string
     */
    public $install = 'Install';

    /**
     * 卸载SQL
     *
     * @var string
     */
    public $uninstall = 'UnInstall';

    /**
     * 更新SQL
     *
     * @var string
     */
    public $upgrade = 'Upgrade';
}
            