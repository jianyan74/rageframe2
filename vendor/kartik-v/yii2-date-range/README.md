yii2-date-range
=================

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-date-range/v/stable)](https://packagist.org/packages/kartik-v/yii2-date-range)
[![Untable Version](https://poser.pugx.org/kartik-v/yii2-date-range/v/unstable)](https://packagist.org/packages/kartik-v/yii2-date-range)
[![License](https://poser.pugx.org/kartik-v/yii2-date-range/license)](https://packagist.org/packages/kartik-v/yii2-date-range)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-date-range/downloads)](https://packagist.org/packages/kartik-v/yii2-date-range)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-date-range/d/monthly)](https://packagist.org/packages/kartik-v/yii2-date-range)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-date-range/d/daily)](https://packagist.org/packages/kartik-v/yii2-date-range)

An advanced date range picker input for Yii Framework 2 based on [dangrossman/bootstrap-daterangepicker plugin](https://github.com/dangrossman/bootstrap-daterangepicker). The date range picker widget is styled for Bootstrap 3.x and 4.x versions and creates a dropdown menu from which a user can select a range of dates. If the plugin is invoked with no options, it will present two calendars to choose a start and end date from. Optionally, you can provide a list of date ranges the user can select from instead of choosing dates from the calendars. If attached to a text input, the selected dates will be inserted into the text box. Otherwise, you can provide a custom callback function to receive the selection.

Additional enhancements added for this widget (by Krajee):

- allows ability to work with Bootstrap input group addons and set the picker position to point at the input-group-addon icon.
- enhanced translation features using yii i18n message files.
- automatically convert format from PHP Date/time format to Moment.js Date/time format.
- automatically trigger change of base field to enforce Yii ActiveField validation
- ability to set the widget to display a preset dropdown of date options within a container (and hidden input).
- style the container options as per your need using templates
- automatically disable date-range based on disabled/readonly options.

### Demo
You can see detailed [documentation](http://demos.krajee.com/date-range) on usage of the extension.

### Release Changes
Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-date-range/blob/master/CHANGE.md) for details of various releases.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

> Note: Check the [composer.json](https://github.com/kartik-v/yii2-date-range/blob/master/composer.json) for this extension's requirements and dependencies.
Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

Either run

```
$ php composer.phar require kartik-v/yii2-date-range "dev-master"
```

or add

```
"kartik-v/yii2-date-range": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

### DateRangePicker

```php
use kartik\daterange\DateRangePicker;
echo DateRangePicker::widget([
    'model'=>$model,
    'attribute'=>'datetime_range',
    'convertFormat'=>true,
    'pluginOptions'=>[
        'timePicker'=>true,
        'timePickerIncrement'=>30,
        'locale'=>[
            'format'=>'Y-m-d h:i A'
        ]
    ]
]);
```
or using seperate min/max attributes on model

```php
use kartik\daterange\DateRangePicker;
echo DateRangePicker::widget([
    'model'=>$model,
    'attribute'=>'datetime_range',
    'convertFormat'=>true,
    'startAttribute'=>'datetime_min',
    'endAttribute'=>'datetime_max',
    'pluginOptions'=>[
        'timePicker'=>true,
        'timePickerIncrement'=>30,
        'locale'=>[
            'format'=>'Y-m-d h:i A'
        ]
    ]
]);
```

### DateRangeBehavior

```php
use kartik\daterange\DateRangeBehavior;

class UserSearch extends User
{
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'createTimeRange',
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ]
        ];
    }

    public function rules()
    {
        return [
            // ...
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['>=', 'createdAt', $this->createTimeStart])
              ->andFilterWhere(['<', 'createdAt', $this->createTimeEnd]);

        return $dataProvider;
    }
}
```

## License

**yii2-date-range** is released under the BSD-3-Clause License. See the bundled `LICENSE.md` for details.