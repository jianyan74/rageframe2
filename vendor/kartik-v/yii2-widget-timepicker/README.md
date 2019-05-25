yii2-widget-timepicker
======================

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-widget-timepicker/v/stable)](https://packagist.org/packages/kartik-v/yii2-widget-timepicker)
[![Untable Version](https://poser.pugx.org/kartik-v/yii2-widget-timepicker/v/unstable)](https://packagist.org/packages/kartik-v/yii2-widget-timepicker)
[![License](https://poser.pugx.org/kartik-v/yii2-widget-timepicker/license)](https://packagist.org/packages/kartik-v/yii2-widget-timepicker)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-widget-timepicker/downloads)](https://packagist.org/packages/kartik-v/yii2-widget-timepicker)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-widget-timepicker/d/monthly)](https://packagist.org/packages/kartik-v/yii2-widget-timepicker)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-widget-timepicker/d/daily)](https://packagist.org/packages/kartik-v/yii2-widget-timepicker)

The TimePicker widget  allows you to easily select a time for a text input using your mouse or keyboards arrow keys. The widget is a wrapper enhancement of the <a href='https://github.com/rendom/bootstrap-3-timepicker' target='_blank'>TimePicker plugin</a> by rendom forked from  <a href='https://github.com/jdewit/bootstrap-timepicker' target='_blank'>jdewit's TimePicker</a>. This widget as used here has been specially enhanced for Yii framework 2.0 and Bootstrap 3. With release v1.0.4, the extension has been enhanced to support Bootstrap 4.x version.

> NOTE: This extension is a sub repo split of [yii2-widgets](https://github.com/kartik-v/yii2-widgets). The split has been done since 08-Nov-2014 to allow developers to install this specific widget in isolation if needed. One can also use the extension the previous way with the whole suite of [yii2-widgets](http://demos.krajee.com/widgets).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). Check the [composer.json](https://github.com/kartik-v/yii2-widget-timepicker/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

To install, either run

```
$ php composer.phar require kartik-v/yii2-widget-timepicker "*"
```

or add

```
"kartik-v/yii2-widget-timepicker": "*"
```

to the ```require``` section of your `composer.json` file.

## Release Changes

> NOTE: Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-widget-timepicker/blob/master/CHANGE.md) for details on changes to various releases.

## Demo

You can refer detailed [documentation and demos](http://demos.krajee.com/widget-details/timepicker) on usage of the extension.

## Usage

```php
use kartik\time\TimePicker;

// usage without model
echo '<label>Start Time</label>';
echo TimePicker::widget([
    'name' => 'start_time', 
    'value' => '11:24 AM',
    'pluginOptions' => [
        'showSeconds' => true
    ]
]);
```

## License

**yii2-widget-timepicker** is released under the BSD-3-Clause License. See the bundled `LICENSE.md` for details.