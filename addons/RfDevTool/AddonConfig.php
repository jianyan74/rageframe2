<?php

namespace addons\RfDevTool;

use addons\RfDevTool\common\components\Bootstrap;
use common\components\BaseAddonConfig;

/**
 * Class Addon
 * @package addons\RfDevTool
 */
class AddonConfig extends BaseAddonConfig
{
    /**
     * 基础信息
     *
     * @var array
     */
    public $info = [
        'name' => 'RfDevTool',
        'title' => '开发工具',
        'brief_introduction' => '提供一些简单的开发辅助工具，比如爬取省市区，数据迁移等等',
        'description' => '让你更加快捷的进行系统开发',
        'author' => '简言',
        'version' => '1.0.0',
    ];

    /**
    * 应用配置
    *
    * 例如：菜单设置/权限设置/快捷入口
    *
    * @var array
    */
    public $appsConfig = [
        'backend' => 'common/config/backend.php',
        'frontend' => 'common/config/frontend.php',
        'merchant' => 'common/config/merchant.php',
        'html5' => 'common/config/html5.php',
        'api' => 'common/config/api.php',
        'oauth2' => 'common/config/oauth2.php',
    ];

    /**
    * 引导文件
    *
    * 设置后系统会在执行插件控制器前执行
    *
    * @var Bootstrap
    */
    public $bootstrap = '';

    /**
     * 参数配置开启
     *
     * @var bool
     */
    public $isSetting = false;

    /**
     * 钩子开启
     *
     * @var bool
     */
    public $isHook = false;

    /**
     * 规则管理开启
     *
     * @var bool
     */
    public $isRule = false;

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
    public $wechatMessage = [];

    /**
     * 保存在当前模块的根目录下面
     *
     * 例如 $install = 'Install';
     * 安装类
     * @var string
     */
    public $install = 'Install';
    
    /**
     * 卸载SQL类
     *
     * @var string
     */
    public $uninstall = 'UnInstall';
    
    /**
     * 更新SQL类
     *
     * @var string
     */
    public $upgrade = 'Upgrade';
}
            