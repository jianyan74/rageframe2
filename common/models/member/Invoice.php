<?php

namespace common\models\member;

use common\behaviors\MerchantBehavior;
use common\enums\StatusEnum;
use common\enums\InvoiceTypeEnum;

/**
 * This is the model class for table "{{%member_invoice}}".
 *
 * @property string $id
 * @property string $member_id 用户id
 * @property string $title 公司抬头
 * @property string $duty_paragraph 税号
 * @property int $is_default 默认
 * @property int $type 类型 1企业 2个人
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Invoice extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['type'], 'in', 'range' => InvoiceTypeEnum::getKeys()],
            [['type'], 'verifyType'],
            [['merchant_id', 'member_id', 'is_default', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'duty_paragraph'], 'string', 'max' => 200],
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
            'title' => '发票抬头',
            'duty_paragraph' => '税号',
            'is_default' => '默认',
            'type' => '类型', // 1企业 2个人
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param $attribute
     */
    public function verifyType($attribute)
    {
        if ($this->type == InvoiceTypeEnum::COMPANY && !$this->duty_paragraph) {
            $this->addError($attribute, '请填写税号');
        }
    }

    /**
     * 关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (($this->isNewRecord || $this->oldAttributes['is_default'] == StatusEnum::DISABLED) && $this->is_default == StatusEnum::ENABLED) {
            self::updateAll(['is_default' => StatusEnum::DISABLED], ['member_id' => $this->member_id, 'is_default' => StatusEnum::ENABLED]);
        }

        if ($this->type == InvoiceTypeEnum::PERSONAGE) {
            $this->duty_paragraph = '';
        }

        return parent::beforeSave($insert);
    }
}
