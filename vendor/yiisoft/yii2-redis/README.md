<p align="center">
    <a href="http://redis.io/" target="_blank" rel="external">
        <img src="http://download.redis.io/logocontest/82.png" height="100px">
    </a>
    <h1 align="center">Redis Cache, Session and ActiveRecord for Yii 2</h1>
    <br>
</p>

This extension provides the [redis](http://redis.io/) key-value store support for the [Yii framework 2.0](http://www.yiiframework.com).
It includes a `Cache` and `Session` storage handler and implements the `ActiveRecord` pattern that allows
you to store active records in redis.

For license information check the [LICENSE](LICENSE.md)-file.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-redis/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-redis)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-redis/downloads.png)](https://packagist.org/packages/yiisoft/yii2-redis)
[![Build Status](https://travis-ci.org/yiisoft/yii2-redis.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-redis)


Requirements
------------

At least redis version 2.6.12 is required for all components to work properly.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiisoft/yii2-redis:"~2.0.0"
```

or add

```json
"yiisoft/yii2-redis": "~2.0.0"
```

to the require section of your composer.json.


Configuration
-------------

To use this extension, you have to configure the Connection class in your application configuration:

```php
return [
    //....
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
    ]
];
```
