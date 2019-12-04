<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@html5', dirname(dirname(__DIR__)) . '/html5');
Yii::setAlias('@services', dirname(dirname(__DIR__)) . '/services');
Yii::setAlias('@storage', dirname(dirname(__DIR__)) . '/storage');
Yii::setAlias('@oauth2', dirname(dirname(__DIR__)) . '/oauth2');
Yii::setAlias('@merchant', dirname(dirname(__DIR__)) . '/merchant');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@root', dirname(dirname(__DIR__)) . '/');
Yii::setAlias('@addons', dirname(dirname(__DIR__)) . '/addons');
// 各自应用域名配置，如果没有配置应用独立域名请忽略
Yii::setAlias('@attachment', dirname(dirname(__DIR__)) . '/web/attachment'); // 本地资源目录绝对路径
Yii::setAlias('@attachurl', '/attachment'); // 资源目前相对路径，可以带独立域名
Yii::setAlias('@backendUrl', '');
Yii::setAlias('@frontendUrl', '');
Yii::setAlias('@html5Url', '');
Yii::setAlias('@apiUrl', '');
Yii::setAlias('@storageUrl', '');
Yii::setAlias('@oauth2Url', '');
Yii::setAlias('@merchantUrl', '');