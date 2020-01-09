<?php

namespace addons\Wechat\common\models;

/**
 * This is the model class for table "rf_wechat_form_id".
 *
 * @property string $id
 * @property string $merchant_id 商户id
 * @property string $form_id formid
 * @property string $stoped_at 失效时间
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class FormId extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_form_id}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'stoped_at', 'created_at', 'updated_at'], 'integer'],
            [['form_id'], 'required'],
            [['form_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'form_id' => 'formid',
            'stoped_at' => '失效时间',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            // 小程序formid有效时间为7天
            $this->stoped_at = time() + 7 * 24 * 60 * 60 - 60;
        }

        return parent::beforeSave($insert);
    }
}