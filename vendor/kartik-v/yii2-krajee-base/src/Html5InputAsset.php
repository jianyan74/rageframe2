<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @package yii2-krajee-base
 * @version 2.0.5
 */

namespace kartik\base;

/**
 * Asset bundle for the [[Html5Input]] widget.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
class Html5InputAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/html5input']);
        parent::init();
    }
}
