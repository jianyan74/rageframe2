<?php

return [
    'id' => 'app-storage',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'glide/index',
    'controllerNamespace' => 'storage\controllers',
    'components' => [
        /** ------ 路由配置 ------ **/
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,  // 这个是生成路由 ?r=site/about--->/site/about
            'showScriptName' => false,
            'rules' =>[
            ],
        ],
        'glide' => [
            'class' => 'trntv\glide\components\Glide',
            'sourcePath' => '@app/web/attachment',
            'cachePath' => '@runtime/glide',
            'signKey' => false // "false" 如果不想用签名请用false
        ],
    ],
    'controllerMap' => [
        'glide' => [
            'class' => 'trntv\glide\controllers\GlideController',
        ],
    ]
];
