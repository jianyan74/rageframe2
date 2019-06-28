<?php
$menuCount = 0;
if (isset($menus['title']))
{
    $menuCount = count($menus['title']);
}

$coverCount = 0;
if (isset($covers['title']))
{
    $coverCount = count($covers['title']);
}

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>;

use addons\<?= $model->name;?>\common\config\Bootstrap;

/**
 * Class Addon
 * @package addons\<?= $model->name . "\r";?>
 */
class AddonConfig
{
    /**
     * 配置信息
     *
     * @var array
     */
    public $info = [
        'name' => '<?= $model['name'];?>',
        'title' => '<?= $model['title'] ?>',
        'brief_introduction' => '<?= $model['brief_introduction'] ?>',
        'description' => '<?= $model['description'] ?>',
        'author' => '<?= $model['author'] ?>',
        'version' => '<?= $model['version'] ?>',
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
    public $isSetting = <?= $model['is_setting'] == true ? 'true' : 'false' ?>;

    /**
     * 钩子
     *
     * @var bool
     */
    public $isHook = <?= $model['is_hook'] == true ? 'true' : 'false' ?>;

    /**
     * 规则管理
     *
     * @var bool
     */
    public $isRule = <?= $model['is_rule'] == true ? 'true' : 'false' ?>;

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
    public $group = '<?= $model['group'] ?>';

    /**
     * 微信接收消息类别
     *
     * @var array
     * 例如 : ['image','voice','video','shortvideo']
     */
    public $wechatMessage = <?= $wechatMessage ?>;

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
<?php for ($i = 0; $i < $menuCount; $i++){
    if (!empty($menus['title'][$i]) && !empty($menus['route'][$i])){
        ?>
        [
            'title' => '<?= $menus['title'][$i]; ?>',
            'route' => '<?= $menus['route'][$i]; ?>',
            'icon' => '<?= $menus['icon'][$i]; ?>'
        ],
<?php }
} ?>
    ];

    /**
     * 同menu上
     *
     * @var array
     */
    public $cover = [
<?php for ($i = 0; $i < $coverCount; $i++){
    if (!empty($covers['title'][$i]) && !empty($covers['route'][$i])){
        ?>
        [
            'title' => '<?= $covers['title'][$i]; ?>',
            'route' => '<?= $covers['route'][$i]; ?>',
            'icon' => '<?= $covers['icon'][$i]; ?>'
        ],
<?php }
}?>
    ];

    /**
     * 保存在当前模块的根目录下面
     *
     * 例如 public $install = 'Install';
     * 安装SQL,只支持php文件
     * @var string
     */
    public $install = '<?= $model['install'] ?>';
    
    /**
     * 卸载SQL
     *
     * @var string
     */
    public $uninstall = '<?= $model['uninstall'] ?>';
    
    /**
     * 更新SQL
     *
     * @var string
     */
    public $upgrade = '<?= $model['upgrade'] ?>';
}
            