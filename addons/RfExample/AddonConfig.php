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
        'title' => '示例管理',
        'brief_introduction' => '系统的功能示例',
        'description' => '系统自带的功能使用示例及其说明，包含一些简单的交互',
        'author' => '简言',
        'version' => '1.0.0',
    ];

    /**
     * 可授权权限
     *
     * 例子：
     *  array(
     *      'index/index' => '首页',
     *      'index/edit' => '首页编辑',
     *  )
     * @var array
     */
    public $authItem = [
        'curd/index' => 'Curd首页',
        'curd/edit' => 'Curd编辑',
        'curd/ajax-update' => 'Curd状态修改',
        'curd/export' => 'Curd导出',
        'curd/delete' => 'Curd删除',
        'grid-curd/index' => 'Grid首页',
        'grid-curd/edit' => 'Grid编辑',
        'grid-curd/ajax-update' => 'Grid状态修改',
        'grid-curd/delete' => 'Grid删除',
        'mongo-db-curd/index' => 'MongoDb首页',
        'mongo-db-curd/edit' => 'MongoDb编辑',
        'mongo-db-curd/ajax-update' => 'MongoDb状态修改',
        'mongo-db-curd/delete' => 'MongoDb删除',
        'elastic-search/index' => 'ES首页',
        'elastic-search/edit' => 'ES编辑',
        'elastic-search/ajax-update' => 'ES状态修改',
        'elastic-search/delete' => 'ES删除',
        'xunsearch/index' => 'Xunsearch首页',
        'xunsearch/edit' => 'Xunsearch编辑',
        'xunsearch/ajax-update' => 'Xunsearch状态修改',
        'xunsearch/delete' => 'Xunsearch删除',
        'queue/index' => '消息队列',
        'cate/index' => '分类首页',
        'cate/ajax-edit' => '分类编辑',
        'cate/ajax-update' => '分类状态修改',
        'cate/delete' => '分类删除',
        'video/cut-image' => '截取视频指定帧',
        'excel/index' => 'excel导入数据',
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
     * 小程序
     *
     * @var bool
     */
    public $isMiniProgram = true;

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
    public $group = 'plug';

     /**
     * 微信接收消息类别
      *
     * @var array
      * 例如 : ['image','voice','video','shortvideo']
     */
    public $wechatMessage = ['image','voice','video','shortvideo'];

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
            'title' => 'Curd',
            'route' => 'curd/index',
            'icon' => ''
        ],
        [
            'title' => 'Curd Grid',
            'route' => 'grid-curd/index',
            'icon' => ''
        ],
        [
            'title' => 'MongoDb Curd',
            'route' => 'mongo-db-curd/index',
            'icon' => ''
        ],
        [
            'title' => 'Elasticsearch',
            'route' => 'elastic-search/index',
            'icon' => ''
        ],
        [
            'title' => 'Xunsearch',
            'route' => 'xunsearch/index',
            'icon' => ''
        ],
        [
            'title' => '消息队列',
            'route' => 'queue/index',
            'icon' => ''
        ],
        [
            'title' => '无限级分类',
            'route' => 'cate/index',
            'icon' => ''
        ],
        [
            'title' => '截取视频指定帧',
            'route' => 'video/cut-image',
            'icon' => ''
        ],
        [
            'title' => 'Excel导入数据',
            'route' => 'excel/index',
            'icon' => ''
        ],
    ];

    /**
     * 同menu上
     *
     * @var array
     */
    public $cover = [
        [
            'title' => '首页导航',
            'route' => 'index/index',
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
            