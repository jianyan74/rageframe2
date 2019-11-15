<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => [
                'class' => \common\components\gii\crud\Generator::class,
                'templates' => [
                    'rageframe' => '@common/components/gii/crud/default',
                    'default' => '@vendor/yiisoft/yii2-gii/src/generators/crud/default',
                ]
            ],
            'model' => [
                'class' => \yii\gii\generators\model\Generator::class,
                'templates' => [
                    'rageframe' => '@common/components/gii/model/default',
                    'default' => '@vendor/yiisoft/yii2-gii/src/generators/model/default',
                ]
            ],
        ],
    ];
}

return $config;
