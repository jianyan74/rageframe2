<?php

namespace backend\modules\member;

use Yii;

/**
 * member module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\member\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        Yii::$app->services->merchant->addId(0);
        // custom initialization code goes here
    }
}
