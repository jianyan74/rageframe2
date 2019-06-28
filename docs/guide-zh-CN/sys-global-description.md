## 全局说明

目录

- 控制台
  - 数据迁移
  - 定时任务
- 公用
  - params 说明和注释
- 后台
  - params 说明和注释
- api
  - params 说明和注释

### 控制台

##### 数据迁移

备份表

```
 # 备份全部表
php ./yii migrate/backup all
 
php ./yii migrate/backup table1,table2,table3... # 备份多张表
php ./yii migrate/backup table1 #备份一张表
```

恢复全部表

```
php ./yii migrate/up
```

##### 定时任务

> 注意需要在Linux环境下运行，且让PHP的system函数取消禁用  
> 表达式帮助：[cron表达式生成器](http://cron.qqe2.com/)

1、需要先设置cron ，让 ./yii schedule/run --scheduleFile=@console/config/schedule.php 可以每分钟运行。

例如:

```
//每分钟执行一次定时任务
* * * * * /path-to-your-project/yii schedule/run --scheduleFile=@console/config/schedule.php 1>> /tmp/rageframeConsoleLog.text 2>&1
```

2、在 console/config/schedule.php 中加入新的定时任务：

```
/**
 * 清理过期的微信历史消息记录
 *
 * 每天凌晨执行一次
 */
$schedule->command('msg-history/index')->cron('0 0 * * *');

/**
 * 定时群发微信消息
 *
 * 每分钟执行一次
 */
$schedule->command('send-message/index')->cron('* * * * *');
```

4、具体例子

查看控制器 `console\controllers\MsgHistoryController`

更多使用文档：https://github.com/omnilight/yii2-scheduling

### 公共

##### params 说明和注释

> 实际内容参考代码

```
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    // 全局上传配置
    'uploadConfig' => [
        // 图片
        'images' => [
            'originalName' => false, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'maxSize' => 1024 * 1024 * 2,// 图片最大上传大小,默认2M
            'extensions' => ["png", "jpg", "jpeg", "gif", "bmp"],// 可上传图片后缀不填写即为不限
            'path' => 'images/', // 图片创建路径
            'subName' => 'Y/m/d', // 图片上传子目录规则
            'prefix' => 'image_', // 图片名称前缀
            'compress' => false, // 是否开启压缩
            'compressibility' => [ // 100不压缩 值越大越清晰 注意先后顺序
                1024 * 100 => 100, // 0 - 100k 内不压缩
                1024 * 1024 => 30, // 100k - 1M 区间压缩质量到30
                1024 * 1024 * 2  => 20, // 1M - 2M 区间压缩质量到20
                1024 * 1024 * 1024  => 10, // 2M - 1G 区间压缩质量到20
            ],
        ],
        // 视频
        'videos' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'maxSize' => 1024 * 1024 * 10,// 最大上传大小,默认10M
            'extensions' => ['mp4'],// 可上传文件后缀不填写即为不限
            'path' => 'videos/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'video_',// 名称前缀
        ],
        // 语音
        'voices' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'maxSize' => 1024 * 1024 * 50,// 最大上传大小,默认50M
            'extensions' => ['amr', 'mp3'],// 可上传文件后缀不填写即为不限
            'path' => 'voices/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'voice_',// 名称前缀
        ],
        // 文件
        'files' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'maxSize' => 1024 * 1024 * 50,// 最大上传大小,默认50M
            'extensions' => [],// 可上传文件后缀不填写即为不限
            'path' => 'files/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'file_',// 名称前缀
        ],
        // 缩略图
        'thumb' => [
            'path' => 'thumb/',// 图片创建路径
        ],
    ],

    /** ------ 微信配置 ------ **/

    // 微信配置 具体可参考EasyWechat
    'wechatConfig' => [],
    // 微信支付配置 具体可参考EasyWechat
    'wechatPaymentConfig' => [],
    // 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [],
    // 微信开放平台第三方平台配置 具体可参考EasyWechat
    'wechatOpenPlatformConfig' => [],
    // 微信企业微信配置 具体可参考EasyWechat
    'wechatWorkConfig' => [],
    // 微信企业微信开放平台 具体可参考EasyWechat
    'wechatOpenWorkConfig' => [],

    /** ------ 微信自定义接口配置------------------- **/

    'userApiPath' => Yii::getAlias('@root') . '/backend/modules/wechat/userapis', // 自定义接口路径
    'userApiNamespace' => '\backend\modules\wechat\userapis', // 命名空间
    'userApiCachePrefixKey' => 'reply:user-api:', // 缓存前缀
];
```

### 后台

##### params 说明和注释

> 实际内容参考代码

```
return [
    'adminEmail' => 'admin@example.com',
    'adminAcronym' => 'RF',
    'adminTitle' => 'RageFrame',

    /** ------ 总管理员配置 ------ **/
    'adminAccount' => 1,// 系统管理员账号id

    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => ['error'], // 级别 ['info', 'warning', 'error']
    'user.log.noPostData' => [ // 安全考虑,不接收Post存储到日志的路由
        'app-backend/site/login',
        'sys/manager/up-password',
        'sys/manager/ajax-edit',
        'member/member/ajax-edit',
    ],
    'user.log.except.code' => [], // 不记录的code

    /** ------ 开发者信息 ------ **/
    'exploitDeveloper' => '简言',
    'exploitFullName' => 'RageFrame应用开发引擎',
    'exploitOfficialWebsite' => '<a href="http://www.rageframe.com" target="_blank">www.rageframe.com</a>',
    'exploitGitHub' => '<a href="https://github.com/jianyan74/rageframe2" target="_blank">https://github.com/jianyan74/rageframe2</a>',

    /** ------ 备份配置配置 ------ **/
    'dataBackupPath' => Yii::getAlias('@root') . '/common/backup', // 数据库备份根路径
    'dataBackPartSize' => 20971520,// 数据库备份卷大小
    'dataBackCompress' => 1,// 压缩级别
    'dataBackCompressLevel' => 9,// 数据库备份文件压缩级别
    'dataBackLock' => 'backup.lock',// 数据库备份缓存文件名

    /**
     * 不需要验证的路由全称
     *
     * 注意: 前面以绝对路径/为开头
     */
    'noAuthRoute' => [
        '/main/index',// 系统主页
        '/main/system',// 系统首页
        '/ueditor/index',// 百度编辑器配置及上传
        '/menu-provinces/index',// 微信个性化菜单省市区
        '/wechat/common/select-news',// 微信自动回复获取图文
        '/wechat/common/select-attachment',// 微信自动回复获取图片/视频/
        '/wechat/analysis/image',// 微信显示素材图片
        '/wechat/qrcode/qr',// 二维码管理的二维码
    ],

    'isMobile' => false,

    /** ------ 配置文本类型 ------ **/
    'configTypeList' => [
        'text' => "文本框",
        'password' => "密码框",
        'secretKeyText' => "密钥文本框",
        'textarea' => "文本域",
        'date' => "日期",
        'time' => "时间",
        'datetime' => "日期时间",
        'dropDownList' => "下拉文本框",
        'radioList' => "单选按钮",
        'checkboxList' => "复选框",
        'baiduUEditor' => "百度编辑器",
        'image' => "图片上传",
        'images' => "多图上传",
        'file' => "文件上传",
        'files' => "多文件上传",
    ],

    /** ------ 插件类型 ------ **/
    'addonsGroup' => [
        'plug' => [
            'name' => 'plug',
            'title' => '功能扩展',
            'icon' => 'fa fa-puzzle-piece',
        ],
        'business' => [
            'name' => 'business',
            'title' => '主要业务',
            'icon' => 'fa fa-random',
        ],
        'customer' => [
            'name' => 'customer',
            'title' => '客户关系',
            'icon' => 'fa fa-rocket',
        ],
        'activity' => [
            'name' => 'activity',
            'title' => '营销及活动',
            'icon' => 'fa fa-tachometer',
        ],
        'services' => [
            'name' => 'services',
            'title' => '常用服务及工具',
            'icon' => 'fa fa-magnet',
        ],
        'biz' => [
            'name' => 'biz',
            'title' => '行业解决方案',
            'icon' => 'fa fa-diamond',
        ],
        'h5game' => [
            'name' => 'h5game',
            'title' => 'H5游戏',
            'icon' => 'fa fa-gamepad',
        ],
    ],

    /** ------ 微信配置-------------------**/

    // 素材类型
    'wechatMediaType' => [
        'news'  => '微信图文',
        'image' => '图片',
        'voice' => '语音',
        'video' => '视频',
    ],

    // 微信级别
    'wechatLevel' => [
        '1' => '普通订阅号',
        '2' => '普通服务号',
        '3' => '认证订阅号',
        '4' => '认证服务号/认证媒体/政府订阅号',
    ],

    /** ------ 微信个性化菜单 ------ **/

    // 性别
    'individuationMenuSex' => [
        '' => '不限',
        1 => '男',
        2 => '女',
    ],

    // 客户端版本
    'individuationMenuClientPlatformType' => [
        '' => '不限',
        1 => 'IOS(苹果)',
        2 => 'Android(安卓)',
        3 => 'Others(其他)',
    ],

    // 语言
    'individuationMenuLanguage' => [
        '' => '不限',
        'zh_CN' => '简体中文',
        'zh_TW' => '繁体中文TW',
        'zh_HK' => '繁体中文HK',
        'en' => '英文',
        'id' => '印尼',
        'ms' => '马来',
        'es' => '西班牙',
        'ko' => '韩国',
        'it' => '意大利',
        'ja' => '日本',
        'pl' => '波兰',
        'pt' => '葡萄牙',
        'ru' => '俄国',
        'th' => '泰文',
        'vi' => '越南',
        'ar' => '阿拉伯语',
        'hi' => '北印度',
        'he' => '希伯来',
        'tr' => '土耳其',
        'de' => '德语',
        'fr' => '法语',
    ],
];
```
### api

##### params 说明和注释

> 实际内容参考代码

```
return [
    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => YII_DEBUG ? ['info', 'warning', 'error'] : ['warning', 'error'], // 级别 ['info', 'warning', 'error']
    'user.log.noPostData' => [ // 安全考虑,不接收Post存储到日志的路由
        'v1/site/login',
    ],
    'user.log.except.code' => [], // 不记录的code
    
    /** ------ token相关 ------ **/
    
    // token有效期是否验证 默认开启验证
    'user.accessTokenValidity' => true,
    // token有效期 默认 2 小时
    'user.accessTokenExpire' => 2 * 60 * 60,
    // refresh token有效期是否验证 默认开启验证
    'user.refreshTokenValidity' => true,
    // refresh token有效期 默认30天
    'user.refreshTokenExpire' => 30 * 24 * 60 * 60,
    // 签名验证默认关闭验证，如果开启需了解签名生成及验证
    'user.httpSignValidity' => false,
    // 签名授权公钥秘钥
    'user.httpSignAccount' => [
        'doormen' => 'e3de3825cfbf',
    ],
];
```