<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug;

use yii\web\AssetBundle;

/**
 * Timeline asset bundle
 *
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @since 2.0.7
 */
class TimelineAsset extends AssetBundle
{
    public $sourcePath = '@yii/debug/assets';
    public $css = [
        'timeline.css',
    ];
    public $js = [
        'timeline.js',
    ];
}