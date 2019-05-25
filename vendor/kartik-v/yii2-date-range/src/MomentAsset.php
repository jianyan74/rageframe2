<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2018
 * @package yii2-date-range
 * @version 1.7.0
 */

namespace kartik\daterange;

use yii\web\View;
use kartik\base\AssetBundle;

/**
 * Moment Asset bundle for \kartik\daterange\DateRangePicker.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class MomentAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $jsOptions = ['position' => View::POS_HEAD];
    /**
     * @inheritdoc
     */
    public $depends = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/moment']);
        parent::init();
    }
}
