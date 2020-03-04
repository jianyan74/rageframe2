<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_auth_allocation}}".
 *
 * @property string $member_id 用户ID
 * @property string $group_id 权限组ID
 * @property string $app_id 应用ID
 */
class AuthAllocation extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_auth_allocation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'group_id'], 'integer'],
            [['app_id'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'group_id' => 'Group ID',
            'app_id' => 'App ID',
        ];
    }
}
