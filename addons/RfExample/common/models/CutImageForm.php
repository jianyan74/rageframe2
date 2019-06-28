<?php
namespace addons\RfExample\common\models;

use yii\base\Model;

/**
 * Class CutImageForm
 * @package addons\RfExample\common\models
 */
class CutImageForm extends Model
{
    public $video;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video'], 'required'],
        ];
    }
}