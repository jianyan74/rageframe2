<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2018
 * @package yii2-date-range
 * @version 1.7.0
 */

namespace kartik\daterange;

use kartik\base\AssetBundle;

/**
 * DateRangePicker bundle for \kartik\daterange\DateRangePicker.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class DateRangePickerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'kartik\daterange\MomentAsset',
        'yii\web\JqueryAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/daterangepicker', 'css/daterangepicker-kv']);
        $this->setupAssets('js', ['js/daterangepicker']);
        parent::init();
    }
}
