yii2-widget-colorinput
======================

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-widget-colorinput/v/stable)](https://packagist.org/packages/kartik-v/yii2-widget-colorinput)
[![Unstable Version](https://poser.pugx.org/kartik-v/yii2-widget-colorinput/v/unstable)](https://packagist.org/packages/kartik-v/yii2-widget-colorinput)
[![License](https://poser.pugx.org/kartik-v/yii2-widget-colorinput/license)](https://packagist.org/packages/kartik-v/yii2-widget-colorinput)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-widget-colorinput/downloads)](https://packagist.org/packages/kartik-v/yii2-widget-colorinput)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-widget-colorinput/d/monthly)](https://packagist.org/packages/kartik-v/yii2-widget-colorinput)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-widget-colorinput/d/daily)](https://packagist.org/packages/kartik-v/yii2-widget-colorinput)

The ColorInput widget is an advanced ColorPicker input styled for Bootstrap. It uses a combination of the HTML5 color input and/or the [JQuery Spectrum Plugin](http://bgrins.github.io/spectrum) for rendering the color picker. You can use the Native HTML5 color input by setting the `useNative` option to `true`. Else, the Spectrum plugin polyfills for unsupported browser versions.

* Specially styled for Bootstrap 3.x and 4.x with customizable caption showing the output of the control.
* Ability to prepend and append addons.
* Allow the input to be changed both via the control or the text box.
* The Spectrum plugin automatically polyfills the `HTML5 color input` for unsupported browser versions.

> NOTE: This extension is a sub repo split of [yii2-widgets](https://github.com/kartik-v/yii2-widgets). The split has been done since 08-Nov-2014 to allow developers to install this specific widget in isolation if needed. One can also use the extension the previous way with the whole suite of [yii2-widgets](http://demos.krajee.com/widgets).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). Check the [composer.json](https://github.com/kartik-v/yii2-widget-colorinput/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

To install, either run

```
$ php composer.phar require kartik-v/yii2-widget-colorinput "*"
```

or add

```
"kartik-v/yii2-widget-colorinput": "*"
```

to the ```require``` section of your `composer.json` file.

## Release Updates

> NOTE: Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-widget-colorinput/blob/master/CHANGE.md) for details on release wise changes.

## Demo

You can refer detailed [documentation and demos](http://demos.krajee.com/widget-details/colorinput) on usage of the extension.

## Usage

```php
use kartik\color\ColorInput;

// Usage with ActiveForm and model
echo $form->field($model, 'color')->widget(ColorInput::classname(), [
    'options' => ['placeholder' => 'Select color ...'],
]);

// With model & without ActiveForm
echo '<label>Select Color</label>';
echo ColorInput::widget([
    'model' => $model,
    'attribute' => 'saturation',
]);
```

## License

**yii2-widget-colorinput** is released under the BSD-3-Clause License. See the bundled `LICENSE.md` for details.