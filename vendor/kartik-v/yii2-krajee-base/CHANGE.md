Change Log: `yii2-krajee-base`
==============================

## Version 2.0.5

**Date:** 13-Mar-2019

- (enh #108): Add new bootstrap CSS Class map `BS_HIDE`.

## Version 2.0.4

**Date:** 04-Dec-2018

- (enh #106): Back and forward browser bug for PJAX.
    - New property `pjaxDuplicationFix` available in widgets/extensions that use `kartik\base\WidgetTrait`
    - The above property if not set will default to `Yii::$app->params['pjaxDuplicationFix']`
    - `Yii::$app->params['pjaxDuplicationFix']` if not set will default to `true`
    - Setting `Yii::$app->params['pjaxDuplicationFix']` will globally control the pjax duplication fix for all Krajee widgets

## Version 2.0.3

**Date:** 27-Nov-2018

- (enh #105): Allow global setting of `AssetBundle::bsDependencyEnabled` via `Yii::$app->params`.

## Version 2.0.2

**Date:** 26-Nov-2018

- (enh #104): Allow Bootstrap Asset Bundle dependency to be configurable.
  - New property `bsDependencyEnabled` in `kartik\base\AssetBundle` (Note that all Krajee extension asset bundles based on bootstrap styles extend this class). The property `bsDependencyEnabled` defaults to `true` whereby based on the `bsVersion`, the asset bundle dependency is set with one of `yii\bootstrap\BootstrapAsset` or `yii\bootstrap4\BootstrapAsset`. If one needs custom control and load their own bootstrap assets - set this property to `false` via yii2 asset manager component in your yii2 application config.

## Version 2.0.1

**Date:** 10-Nov-2018

- Return widget instance in `addCssClass` & `removeCssClass` methods in `BootstrapTrait` for easy chaining of these methods.
- Update composer dependency to suggest `yii2-bootstrap` or `yii2-bootstrap4` installs.
- (enh #102): Add BS_PANEL_DEFAULT contextual style.
- Update README.

## Version 2.0.0

**Date:** 09-Oct-2018

- (enh #101): Remove composer dependency on yii2 bootstrap.
- Enhancements to `bsVersion` initialization.

## Version 1.9.9

**Date:** 27-Sep-2018

- Enhance `BootstrapTrait::getCssClass` method.

## Version 1.9.8

**Date:** 27-Sep-2018

- Bump up version.

## Version 1.9.7

**Date:** 26-Sep-2018

- (enh #100): Configurable conversion classes between Bootstrap Versions.

## Version 1.9.6

**Date:** 22-Sep-2018

- Implement `BootstrapInterface` and `bsColCssPrefixes`.

## Version 1.9.5

**Date:** 20-Sep-2018

- Enhance `Config` to include `Config::hasCssClass` method.

## Version 1.9.4

**Date:** 05-Sep-2018

- (bug #95): Correct `InputWidget`. 
- (enh #94): Refactor code and consolidate / optimize properties within traits. 
- Add Bootstrap button default CSS and icon prefix parsing.

## Version 1.9.3

**Date:** 29-Aug-2018

- Update `Module` to include `bsVersion` configuration.
- Update `AssetBundle` to include new method `registerBundle` for reading BS Version from widgets.
- Update `BootstrapTrait` to include better BS Version parsing methods.

## Version 1.9.2

**Date:** 20-Aug-2018

- Enhance bootstrap 4 addon rendering.
- Reformat and reorganize code.
- Update animate.css to release v3.7.0.

## Version 1.9.1

**Date:** 16-Aug-2018

- (enh #92): Remove `yii2-bootstrap4` dependency.
- (enh #90): Implement AddonTrait.

## Version 1.9.0

**Date:** 12-Aug-2018

- Refactor code for Bootstrap 4 support.
- (enh #87): Update `animate.css` to latest release.
- (enh #86): Refactor source code into `src` folder.
- (enh #85): Add bootstrap 4 support.
- (enh #83): Correct CheckboxX repo in Config class.
- (enh #82): Enhance `getModule` to retrieve correct module.

## Version 1.8.9

**Date:** 29-Sep-2017

- (enh #80): Add ability to configure module id in widgets.
- (enh #79): New property `hashVarLoadPosition` for InputWidget and Widget.
- (enh kartik-v/yii2-widget-switchinput#29): Enhance boolean inputs for better value init in non model scenario.

## Version 1.8.8

**Date:** 22-Feb-2017

- (enh #74): Allow registering variables in closures by setting them in global namespace.
- (enh #75): Better validation for default attribute value.
- (enh #76): Create `BaseAssetBundle` without JQuery and Bootstrap assets dependency.

## Version 1.8.7

**Date:** 04-Sep-2016

- (enh #72): Set a timeout for plugin events callbacks.
- (enh #73): Update Animate CSS assets to latest version.
- Enhance PHP Documentation for all classes and methods in the extension.

## Version 1.8.6

**Date:** 19-Aug-2016

- (enh #67): Update Latvian translations.
- (enh #71): Simplify plugin initialization and event handling.
- Add github contribution templates.

## Version 1.8.5

**Date:** 08-May-2016

- (enh #60): New property `enablePopStateFix` (addendum to #59).
- (enh #61): Enhance event handling for `pluginEvents`.
- (enh #62): Enhance widgets and plugin initialization for dynamic manipulation.
    - Plugin initialization routine revamped
    - Widget output can be better handled with dynamic manipulation e.g. via ajax. In order to achieve this the widgets now use the `document.body` on `load` event and trap the plugin selector identifier within that.
    - More better integration with JS frameworks like AngularJS
    - Revamp initialization of HTML5 Input based plugins including `Html5Input` widget init.
- (bug #63): More correct plugin event listening and fixes #61 better.
- (enh #64): Correct plugin event listening for all use cases (reverts #61).
- (enh #65): Remove AssetBundle dependency on `yii\web\YiiAsset`.

## Version 1.8.4

**Date:** 11-Apr-2016

- Better enhanced fix for #59.

## Version 1.8.3

**Date:** 09-Apr-2016

- (enh #56): Correct date range picker repo in Config.
- (enh #57): Update animate.css to v3.5.1.
- (enh #58): Configure TranslationTrait to accept and parse global i18n config.
- Add branch alias for dev-master latest release.
- (enh #59): Workaround PJAX fix for weird back / forward browser button behavior.

## Version 1.8.2

**Date:** 10-Jan-2016

- (enh #52): Enhance widget trait for better PJAX initialization.
- (enh #53): New widget property `pluginDestroyJs` for destroying plugin before initialization

## Version 1.8.1

**Date:** 27-Dec-2015

- (enh #50): Enhance Html5Input to accept more input types.
- (enh #51): Add plugin loading CSS for HTML5 input.

## Version 1.8.0

**Date:** 11-Dec-2015

- (bug #49): Correct module and sub-module validation in `Config::getModule`.

## Version 1.7.9

**Date:** 25-Nov-2015

- (bug #48): Correct initialization of i18N.

## Version 1.7.8

**Date:** 22-Nov-2015

- (enh #40): Various enhancements for PJAX  .
- (enh #41): Added .gitignore for Composer stuff.
- (bug #42): Better and more correct date format parsing.
- (enh #46): Enhance `Html5Input` initialization.
- (enh #47): Implement message translations.

## Version 1.7.7

**Date:** 16-Jun-2015

- (enh #37): Set range input caption to change during slide.
- (enh #38): Update to use `Json::htmlEncode` as per yii release v2.0.4.

## Version 1.7.6

**Date:** 09-May-2015

- (enh #36): Add kv-input-group-hide class.

## Version 1.7.5

**Date:** 03-May-2015

- (enh #30): Improve translation trait for determining messages folder.
- (enh #33): Better styling of html5 inputs.
- (enh #34): New `addLanguage` method in `AssetBundle`.
- (enh #35): Allow extending translation messages.

## Version 1.7.4

**Date:** 13-Feb-2015

- (enh #28): Create WidgetTrait for better code reuse.
- Code cleanup and reformatting.
- (enh #29): New `getModule` and `initModule` methods in `Config`.
- Set copyright year to current.

## Version 1.7.3

**Date:** 25-Jan-2015

- (enh #26): Enhance `Widget` options to store multiple plugin config.
- (enh #27): Set directory and URL separator rightly for the setLanguage validation.

## Version 1.7.2

**Date:** 20-Jan-2015

- (enh #24): Revert use of DIRECTORY_SEPARATOR and use forward slash instead.

## Version 1.7.1

**Date:** 15-Jan-2015

- (enh #23): Enhance AssetBundle for over riding empty assets from AssetManager.

## Version 1.7.0

**Date:** 12-Jan-2015

- Fix kartik-v/yii2-widget-datepicker#10 - Language definition in pluginOptions
- (enh #21): Implement TranslationTrait and i18N configuration.
- (enh #22): Implement base Module class.
- Code formatting updates as per Yii2 coding style.

## Version 1.6.0

**Date:** 16-Dec-2014

- (bug #16): variable `$short` in `InputWidget` in method `setLanguage` set without `$prefix`.
- (bug #17): Enhance `parseDateFormat` to convert formats rightly to PHP DateTime format.
- (bug #18): Better `noSupport` message translation in `Html5Input`.
- (enh #19): Avoid inspect errors in IDE for `Html5Input`.
- (enh #20): Add new PluginAssetBundle for bootstrap JS dependent plugins.

## Version 1.5.0

**Date:** 06-Dec-2014

- (enh #11): Added new properties `disabled` and `readonly` to `InputWidget` and `Html5Input`.
    - a new method `initDisability` is been created for disability validation across Input Widgets
    - this will automatically set the input's `disabled` or `readonly` option
    - it will also automatically be used to validate disability and style complex widgets like `DatePicker` or `DateTimePicker`
- (enh #12): Enhance `InputWidget` to include `getPluginScript` method.    
- (enh #13): Enhancements to Config helper and change `self` methods to `static`.
- (bug #14): Fix line terminators and new lines in `getPluginScript`.

## Version 1.4.0

**Date:** 29-Nov-2014

- (enh #9): Enhanced language validation for combined ISO-639 and ISO-3166 codes
    - Auto detect and generate the plugin language and its related locale file using a new `setLanguage` method in `InputWidget`
    - Enhance `initLanguage` method to include a parameter `full` which defaults to `false` to use the ISO-639 language code.
- (enh #10): Enhanced language and directory methods in Config

    Two new methods is added to Config helper class:

    - `getCurrentDir` - gets the current directory of the extended class object
    - `fileExists` - modified file_exists method after replacing the slashes with right directory separator

## Version 1.3.0

**Date:** 25-Nov-2014

- (enh #6): Enhance `InputWidget` for attaching multiple jQuery plugins.

### BC Breaking Changes

#### Removed:
The following HTML5 data attributes are removed and not registered anymore with the input:

- `data-plugin-name` the name of the plugin
- `data-plugin-options` the client options of the plugin

#### Added:

Following functionality included in `InputWidget` and `Widget` classes:

- New protected property `_pluginName` for easy configuration in individual widgets.
- The following HTML5 data attribute will be added for each input:
    - `data-krajee-{name}` the client options of the plugin. The tag `{name}` will be replaced with the 
       registered jQuery plugin name (e.g. `select2`, `typeahead` etc.).
- New protected property `_dataVar` included for generating the above data attribute.


## Version 1.2.0

**Date:** 25-Nov-2014

- (bug #2): AssetBundle::EMPTY_PATH is not setting sourcePath to null.
- (enh #3): Modify and validate language setting according to yii i18n.
- (enh #4): Add validations for html inputs, dropdowns, and widgets in `Config`.
- (enh #5): Correctly validate checkbox and radio checked states for `InputWidget`.

## Version 1.1.0

**Date:** 10-Nov-2014

- Validation for sub repositories containing input widgets.
- (bug #1): Include namespaced FormatConverter class in InputWidget.
- Include `Html5Input` class and  `Html5InputAsset` bundle.
- Include `AnimateAsset` bundle.
- Code formatting as per standards.

## Version 1.0.0

**Date:** 06-Nov-2014

Initial release