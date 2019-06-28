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
        'brief_introduction' => '内置基础的单页，文章管理、幻灯片等',
        'description' => '文章管理',
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
            'article-single/index' => '单页管理',
            'article-single/edit' => '单页编辑',
            'article-single/ajax-update' => '单页状态修改',
            'article-single/delete' => '单页删除',
            'article/index' => '文章首页',
            'article/edit' => '文章编辑',
            'article/ajax-update' => '文章状态修改',
            'article/hide' => '文章删除',
            'article-cate/index' => '文章分类首页',
            'article-cate/ajax-edit' => '文章分类编辑',
            'article-cate/ajax-update' => '文章分类状态修改',
            'article-cate/delete' => '文章分类删除',
            'article-tag/index' => '文章标签首页',
            'article-tag/ajax-edit' => '文章标签编辑',
            'article-tag/ajax-update' => '文章标签状态修改',
            'article-tag/delete' => '文章标签删除',
            'adv/index' => '幻灯片首页',
            'adv/edit' => '幻灯片编辑',
            'adv/ajax-update' => '幻灯片状态修改',
            'adv/delete' => '幻灯片删除',
            'article/recycle' => '回收站',
            'article/show' => '回收站还原',
            'article/delete' => '回收站删除',
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
    public $isHook = true;

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
            'title' => '幻灯片',
            'route' => 'adv/index',
            'icon' => 'fa fa-file-image-o'
        ],
        [
            'title' => '回收站',
            'route' => 'article/recycle',
            'icon' => 'fa fa-trash-o'
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
        ]
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
            