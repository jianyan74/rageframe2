<?php
namespace backend\modules\wechat\models;

use Yii;
use yii\base\Model;

/**
 * Class PreviewForm
 * @package backend\modules\wechat\models
 */
class PreviewForm extends Model
{
    /**
     * @var int
     */
    public $type = 1;

    /**
     * @var
     */
    public $content;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['type'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => '类别',
            'content' => '微信号/openid',
        ];
    }
}