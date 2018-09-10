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
                'class' => yii\gii\generators\crud\Generator::className(),
                'templates' => [
                    'default' => '@vendor/yiisoft/yii2-gii/src/generators/crud/default',
                    'rageframe' => '@backend/components/gii/crud/default',
                ]
            ]
        ],
    ];
}

return $config;
