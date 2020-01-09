<?php

echo "<?php\n";
?>

namespace addons\<?= $model->name;?>;

use common\components\BaseAddonConfig;
use addons\<?= $model->name;?>\common\components\Bootstrap;

/**
 * Class Addon
 * @package addons\<?= $model->name . "\r";?>
 */
class AddonConfig extends BaseAddonConfig
{
    /**
     * 基础信息
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
    * @var string
    */
    public $service = '';

    /**
     * 参数配置开启
     *
     * @var bool
     */
    public $isSetting = <?= $model['is_setting'] == true ? 'true' : 'false' ?>;

    /**
     * 规则管理开启
     *
     * @var bool
     */
    public $isRule = <?= $model['is_rule'] == true ? 'true' : 'false' ?>;

    /**
    * 商户路由映射
    *
    * 开启后无需再去商户应用端去开发程序，直接映射后台应用的控制器方法过去，菜单权限还需要单独配置
    *
    * @var bool
    */
    public $isMerchantRouteMap = <?= $model['is_merchant_route_map'] == true ? 'true' : 'false' ?>;

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
     * 保存在当前模块的根目录下面
     *
     * 例如 $install = 'Install';
     * 安装类
     * @var string
     */
    public $install = '<?= $model['install'] ?>';
    
    /**
     * 卸载SQL类
     *
     * @var string
     */
    public $uninstall = '<?= $model['uninstall'] ?>';
    
    /**
     * 更新SQL类
     *
     * @var string
     */
    public $upgrade = '<?= $model['upgrade'] ?>';
}
            