<?php

namespace addons\Wechat;

use common\components\BaseAddonConfig;
use addons\Wechat\services\Application;
use addons\Wechat\common\components\Bootstrap;

/**
 * Class Addon
 * @package addons\Wechat
 */
class AddonConfig extends BaseAddonConfig
{
    /**
     * 基础信息
     *
     * @var array
     */
    public $info = [
        'name' => 'Wechat',
        'title' => '微信公众号',
        'brief_introduction' => '微信公众号基础功能管理',
        'description' => '管理公众号的菜单、素材、消息群发、数据统计等',
        'author' => '简言',
        'version' => '1.0.3',
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
    public $bootstrap = Bootstrap::class;

    /**
    * 服务层
    *
    * 设置后系统会自动注册
    *
    * 调用方式
    *
    * Yii::$app->插件名称 + Services
    *
    * 例如
    *
    * Yii::$app->tinyShopServices;
    *
    * @var Application
    */
    public $service = Application::class;

    /**
     * 参数配置开启
     *
     * @var bool
     */
    public $isSetting = false;

    /**
     * 规则管理开启
     *
     * @var bool
     */
    public $isRule = false;

    /**
     * 商户路由映射
     *
     * 开启后无需再去商户应用端去开发程序，直接映射后台应用的控制器方法过去，菜单权限还需要单独配置
     *
     * @var bool
     */
    public $isMerchantRouteMap = true;

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
    public $group = 'plug';

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
            