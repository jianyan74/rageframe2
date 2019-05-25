<h1 align="center">
    <a href="http://demos.krajee.com" title="Krajee Demos" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/krajee-logo-b.png" alt="Krajee Logo"/>
    </a>
    <br>
    yii2-krajee-base
    <hr>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DTP3NZQ6G2AYU"
       title="Donate via Paypal" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/donate.png" alt="Donate"/>
    </a>
</h1>

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-krajee-base/v/stable)](https://packagist.org/packages/kartik-v/yii2-krajee-base)
[![Unstable Version](https://poser.pugx.org/kartik-v/yii2-krajee-base/v/unstable)](https://packagist.org/packages/kartik-v/yii2-krajee-base)
[![License](https://poser.pugx.org/kartik-v/yii2-krajee-base/license)](https://packagist.org/packages/kartik-v/yii2-krajee-base)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-krajee-base/downloads)](https://packagist.org/packages/kartik-v/yii2-krajee-base)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-krajee-base/d/monthly)](https://packagist.org/packages/kartik-v/yii2-krajee-base)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-krajee-base/d/daily)](https://packagist.org/packages/kartik-v/yii2-krajee-base)

This is a base library with set of foundation classes and components used by all [Yii2 extensions by Krajee](http://demos.krajee.com). One can use this base library during creation of one's own extensions if needed.

> NOTE: This extension depends on the [yiisoft/yii2-bootstrap](https://github.com/yiisoft/yii2-bootstrap) and/or [yiisoft/yii2-bootstrap4](https://github.com/yiisoft/yii2-bootstrap4) extensions. Check the [composer.json](https://github.com/kartik-v/yii2-krajee-base/blob/master/composer.json) for this extension's requirements and dependencies. 

## Why this extension?
To ensure a leaner code base / foundation component for use in all Krajee extensions (e.g. yii2-widgets, yii2-datecontrol, yii2-grid, yii2-dynagrid etc.). This should allow most developers to plug and play components only they need, without needing the complete suite of widgets. For example, this mitigates [this issue](https://github.com/kartik-v/yii2-grid/issues/123). 

## Release Changes

> NOTE: Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-krajee-base/blob/master/CHANGE.md) for details on changes to various releases.

## Extension Classes

### [Module](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/Module.php)
Extends [Yii Module](https://github.com/yiisoft/yii2/blob/master/framework/base/Module.php) class for Krajee's Yii2 widgets and usage with translation properties enabled. 

### [Widget](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/Widget.php)
Extends [Yii Widget](https://github.com/yiisoft/yii2/blob/master/framework/base/Widget.php) class for Krajee's Yii2 widgets and usage with bootstrap CSS framework. 

### [InputWidget](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/InputWidget.php)
Extends [Yii InputWidget](https://github.com/yiisoft/yii2/blob/master/framework/widgets/InputWidget.php) class for Krajee's Yii2 widgets and usage with bootstrap CSS framework. With release v1.3.0, the Input widget automatically now attaches the following HTML5 data attribute for each input that registers jQuery plugins via `registerPlugin` method:

- `data-krajee-{name}` the client options of the plugin. The tag `{name}` will be replaced with the registered jQuery plugin name (e.g. `select2`, `typeahead` etc.).

### [TranslationTrait](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/TranslationTrait.php)
A trait for handling translation functionality using Yii's i18n components.

### [BootstrapInterface](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/BootstrapInterface.php)
The interface that defines the constants and method signatures for bootstrap 3.x and 4.x versions that will be implemented in all classes that use `BootstrapTrait`.

### [BootstrapTrait](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/BootstrapTrait.php)
A trait for bootstrap 3.x and 4.x version parsing functionality for Krajee extensions (the class using this trait must implement `BootstrapInterface`).

### [AddonTrait](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/AddonTrait.php)
A trait for rendering bootstrap 3.x or 4.x input group addons. 

### [WidgetTrait](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/WidgetTrait.php)
A trait for Krajee widgets including prebuilt methods for plugin registration.
	
### [AssetBundle](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/AssetBundle.php)
Extends [Yii AssetBundle](https://github.com/yiisoft/yii2/blob/master/framework/web/AssetBundle.php) class for Krajee's Yii2 widgets with enhancements for using minimized CSS and JS based on debug mode.

### [PluginAssetBundle](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/PluginAssetBundle.php)
Extension of the above [AssetBundle](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/AssetBundle.php) to include dependency on Bootstrap assets and javascript plugins.

### [AnimateAsset](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/AnimateAsset.php)
An asset bundle for loading various CSS3 animations and effects.

### [Html5Input](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/Html5Input.php)
A modified input widget for rendering HTML5 inputs with bootstrap styling and input group addons for Krajee's Yii 2 extensions.

### [Config](https://github.com/kartik-v/yii2-krajee-base/blob/master/src/Config.php)
A global configuration and validation helper class for usage across Krajee's Yii 2 extensions.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

> Note: Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

Either run

```
$ php composer.phar require kartik-v/yii2-krajee-base "dev-master"
```

or add

```
"kartik-v/yii2-krajee-base": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## License

**yii2-krajee-base** is released under the BSD-3-Clause License. See the bundled `LICENSE.md` for details.