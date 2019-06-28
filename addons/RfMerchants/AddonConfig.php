<?php
namespace addons\RfMerchants;

/**
 * Class Addon
 * @package addons\RfMerchants
 */
class AddonConfig
{
    /**
     * 配置信息
     *
     * @var array
     */
    public $info = [
        'name' => 'RfMerchants',
        'title' => '多商户',
        'brief_introduction' => '多商户管理',
        'description' => '管理多个商户信息，支持商户入驻',
        'author' => '简言',
        'version' => '1.0.0',
    ];

    /**
     * 可授权权限
     *
     * 注意：采用Yii2的路由命名方式
     * 例子：array(
     *          'index/index' => '首页',
     *          'cate-index/index' => '分类首页',
     *          'cate-index/first-data' => '分类数据',
     *        )
     * @var array
     */
    public $authItem = [
        'backend' => [
            'merchant/index' => '商户管理',
            'merchant/ajax-edit' => '商户编辑',
            'merchant/ajax-update' => '商户状态修改',
            'merchant/delete' => '商户删除',
            'role/index' => '角色管理',
            'role/edit' => '角色编辑',
            'role/ajax-update' => '角色状态修改',
            'role/delete' => '角色删除',
            'manager/index' => '用户管理',
            'manager/edit' => '用户编辑',
            'manager/ajax-edit' => '用户账号密码',
            'manager/ajax-update' => '用户状态修改',
            'manager/destroy' => '用户删除',
        ],
        'frontend' => [
        ],
        'wechat' => [
        ],
        'api' => [
        ],
    ];

    /**
     * 参数配置
     *
     * @var bool
     */
    public $isSetting = false;

    /**
     * 钩子
     *
     * @var bool
     */
    public $isHook = false;

    /**
     * 规则管理
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
    public $group = 'plug';

    /**
     * 微信接收消息类别
     *
     * @var array
     * 例如 : ['image','voice','video','shortvideo']
     */
    public $wechatMessage = [];

    /**
     * 后台菜单
     *
     * 例如
     * public $menu = [
     *  [
     *      'title' => '基本表格',
     *      'route' => 'curd-base/index',
     *      'icon' => ''
     *   ]
     * ]
     * @var array
     */
    public $menu = [
        [
            'title' => '商户管理',
            'route' => 'merchant/index',
            'icon' => ''
        ],
    ];

    /**
     * 同menu上
     *
     * @var array
     */
    public $cover = [
    ];

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
            