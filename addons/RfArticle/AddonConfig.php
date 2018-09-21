<?php
namespace addons\RfArticle;

/**
 * Class Addon
 * @package addons\RfArticle
 */
class AddonConfig
{
    /**
     * 配置信息
     *
     * @var array
     */
    public $info = [
        'name' => 'RfArticle',
        'title' => '内容管理',
        'brief_introduction' => '内置基础的单页，文章管理',
        'description' => '文章管理',
        'author' => '简言',
        'version' => '1.0.0',
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
    public $isHook = true;

    /**
     * 小程序
     *
     * @var bool
     */
    public $isMiniProgram = false;

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
            'title' => '单页管理',
            'route' => 'article-single/index',
            'icon' => 'fa fa-pencil-square-o'
        ],
        [
            'title' => '文章管理',
            'route' => 'article/index',
            'icon' => 'fa fa-list'
        ],
        [
            'title' => '文章分类',
            'route' => 'article-cate/index',
            'icon' => ''
        ],
        [
            'title' => '文章标签',
            'route' => 'article-tag/index',
            'icon' => 'fa fa-tags'
        ],
        [
            'title' => '回收站',
            'route' => 'article/recycle',
            'icon' => 'fa fa-trash'
        ],
    ];

    /**
     * 同menu上
     *
     * @var array
     */
    public $cover = [
        [
            'title' => '首页入口',
            'route' => 'index/index',
            'icon' => ''
        ],
    ];

    /**
     * 保存在当前模块的根目录下面
     *
     * 例如 public $install = 'install.php';
     * 安装SQL,只支持php文件
     * @var string
     */
    public $install = 'install.php';
    
    /**
     * 卸载SQL
     *
     * @var string
     */
    public $uninstall = 'uninstall.php';
    
    /**
     * 更新SQL
     *
     * @var string
     */
    public $upgrade = 'upgrade.php';
}
            