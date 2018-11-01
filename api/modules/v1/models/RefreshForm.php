<?php
namespace api\modules\v1\models;

use yii\base\Model;

/**
 * Class RefreshForm
 * @package api\modules\v1\models
 */
class RefreshForm extends Model
{
    public $group;

    public $refresh_token;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['refresh_token', 'group'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'refresh_token' => '重置令牌',
            'group' => '组别',
        ];
    }
}