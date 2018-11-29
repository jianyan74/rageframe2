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
            'title' => 'Curd For Grid',
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
            