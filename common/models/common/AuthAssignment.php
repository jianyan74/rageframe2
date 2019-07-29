<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_auth_assignment}}".
 *
 * @property int $role_id
 * @property int $user_id
 * @property string $type 类型
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_auth_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'user_id'], 'required'],
            [['role_id', 'user_id'], 'integer'],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '角色',
            'user_id' => '用户',
            'type' => '类别',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(AuthRole::class,
            ['id' => 'role_id'])->where(['merchant_id' => Yii::$app->services->merchant->getId()]);
    }
}
