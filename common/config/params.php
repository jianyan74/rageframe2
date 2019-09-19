<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    // 是否在模块内
    'inAddon' => false,
    // 多商户开启
    'merchantOpen' => true,
    // 系统管理员账号id
    'adminAccount' => '',
    // 请求全局唯一ID
    'uuid' => '',
    // 百度编辑器默认上传驱动
    'UEditorUploadDrive' => 'local',
    // 全局上传配置
    'uploadConfig' => [
        // 图片
        'images' => [
            'originalName' => false, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'maxSize' => 1024 * 1024 * 10,// 图片最大上传大小,默认10M
            'extensions' => ["png", "jpg", "jpeg", "gif", "bmp"],// 可上传图片后缀不填写即为不限
            'path' => 'images/', // 图片创建路径
            'subName' => 'Y/m/d', // 图片上传子目录规则
            'prefix' => 'image_', // 图片名称前缀
            'mimeTypes' => 'image/*', // 媒体类型
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
            'maxSize' => 1024 * 1024 * 50,// 最大上传大小,默认50M
            'extensions' => ['mp4'],// 可上传文件后缀不填写即为不限
            'path' => 'videos/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'video_',// 名称前缀
            'mimeTypes' => 'video/*', // 媒体类型
        ],
        // 语音
        'voices' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'maxSize' => 1024 * 1024 * 30,// 最大上传大小,默认30M
            'extensions' => ['amr', 'mp3'],// 可上传文件后缀不填写即为不限
            'path' => 'voice/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'voice_',// 名称前缀
            'mimeTypes' => 'image/*', // 媒体类型
        ],
        // 文件
        'files' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'maxSize' => 1024 * 1024 * 150,// 最大上传大小,默认150M
            'extensions' => [],// 可上传文件后缀不填写即为不限
            'path' => 'files/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'file_',// 名称前缀
            'mimeTypes' => '*', // 媒体类型
            'blacklist' => [ // 文件后缀黑名单
                'php', 'php5', 'php4', 'php3', 'php2', 'php1',
                'java', 'asp', 'jsp', 'jspa', 'javac',
                'py', 'pl', 'rb', 'sh', 'ini', 'svg', 'html', 'jtml','phtml','pht', 'js'
            ],
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
    'userApiCachePrefixKey' => 'wechat:reply:user-api:', // 缓存前缀
];
