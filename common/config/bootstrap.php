<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@wechat', dirname(dirname(__DIR__)) . '/wechat');
Yii::setAlias('@storage', dirname(dirname(__DIR__)) . '/storage');
Yii::setAlias('@oauth2', dirname(dirname(__DIR__)) . '/oauth2');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@root', dirname(dirname(__DIR__)) . '/');
Yii::setAlias('@addons', dirname(dirname(__DIR__)) . '/addons');
// 各自应用域名配置，如果没有配置应用独立域名请忽略
Yii::setAlias('@attachment', dirname(dirname(__DIR__)) . '/web/attachment'); // 本地资源目录绝对路径
Yii::setAlias('@attachurl', '/attachment'); // 资源目前相对路径，可以带独立域名
Yii::setAlias('@backendUrl', '');
Yii::setAlias('@frontendUrl', '');
Yii::setAlias('@wechatUrl', '');
Yii::setAlias('@apiUrl', '');
Yii::setAlias('@storageUrl', '');
Yii::setAlias('@oauth2Url', '');
