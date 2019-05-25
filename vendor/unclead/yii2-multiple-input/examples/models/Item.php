<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput\examples\models;

use Yii;
use yii\base\Model;

/**
 * Class Item
 * @package unclead\multipleinput\examples\models
 */
class Item extends Model
{
    public $id;

    public $title;

    public $description;

    public $file;

    public $date;


    public function behaviors()
    {
        return [
            // you have to install https://github.com/vova07/yii2-fileapi-widget
            /*
            'uploadBehavior' => [
                'class' => \vova07\fileapi\behaviors\UploadBehavior::className(),
                'attributes' => [
                    'file' => [
                        'path' => Yii::getAlias('@webroot') . '/images/',
                        'tempPath' => Yii::getAlias('@webroot') . '/images/tmp/',
                        'url' => '/images/'
                    ],
                ]
            ]*/
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['title'], 'string', 'min' => 5, 'max' => 64],
            [['id', 'file'], 'safe']
        ];
    }


}