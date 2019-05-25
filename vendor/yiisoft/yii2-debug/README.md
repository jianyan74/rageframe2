<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Debug Extension for Yii 2</h1>
    <br>
</p>

This extension provides a debugger for [Yii framework 2.0](http://www.yiiframework.com) applications. When this extension is used,
a debugger toolbar will appear at the bottom of every page. The extension also provides
a set of standalone pages to display more detailed debug information.

For license information check the [LICENSE](LICENSE.md)-file.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-debug/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-debug)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-debug/downloads.png)](https://packagist.org/packages/yiisoft/yii2-debug)
[![Build Status](https://travis-ci.org/yiisoft/yii2-debug.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-debug)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiisoft/yii2-debug
```

or add

```
"yiisoft/yii2-debug": "~2.0.0"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'bootstrap' => ['debug'],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            // uncomment and adjust the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
        ],
        // ...
    ],
    ...
];
```

You will see a debugger toolbar showing at the bottom of every page of your application.
You can click on the toolbar to see more detailed debug information.


Open Files in IDE
-----

You can create a link to open files in your favorite IDE with this configuration:

```php
return [
    'bootstrap' => ['debug'],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'traceLine' => '<a href="phpstorm://open?url={file}&line={line}">{file}:{line}</a>',
            // uncomment and adjust the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
        ],
        // ...
    ],
    ...
];
```

You must make some changes to your OS. See these examples: 
 - PHPStorm: https://github.com/aik099/PhpStormProtocol
 - Sublime Text 3 on Windows or Linux: https://packagecontrol.io/packages/subl%20protocol
 - Sublime Text 3 on Mac: https://github.com/inopinatus/sublime_url

#### Virtualized or dockerized

If your application is run under a virtualized or dockerized environment, it is often the case that the application's base path is different inside of the virtual machine or container than on your host machine. For the links work in those situations, you can configure `traceLine` like this (change the path to your app):

```php
'traceLine' => function($options, $panel) {
    $filePath = str_replace(Yii::$app->basePath, '~/path/to/your/app', $options['file']);
    return strtr('<a href="ide://open?url=file://{file}&line={line}">{text}</a>', ['{file}' => $filePath]);
},
```
You can add all posible path like this (valid for advanced template backend main-local.php config):

```php
'traceLine' => function($options, $panel) {
    $filePath = $options['file'];
    $filePath = str_replace(Yii::$app->basePath, 'file://~/path/to/your/backend', $filePath);
    $filePath = str_replace(dirname(Yii::$app->basePath) . '/common' , 'file://~/path/to/your/common', $filePath);
    $filePath = str_replace(Yii::$app->vendorPath, 'file://~/path/to/your/vendor', $filePath);
    return strtr('<a href="phpstorm://open?url={file}&line={line}">{file}:{line}</a>', ['{file}' => $filePath]);
},
```
