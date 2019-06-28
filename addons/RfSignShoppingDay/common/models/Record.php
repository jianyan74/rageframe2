<?php

namespace addons\RfSignShoppingDay\common\models;

use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_RfSign_shopping_street_record}}".
 *
 * @property int $id
 * @property string $openid
 * @property int $is_win
 * @property string $award_id
 * @property string $award_title
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Record extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_sign_shopping_street_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid'], 'required'],
            [['merchant_id', 'award_cate_id', 'is_win', 'status', 'created_at', 'updated_at', 'award_id'], 'integer'],
            [['openid'], 'string', 'max' => 50],
            ['record_date', 'date'],
            [['award_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'is_win' => 'Is Win',
            'award_id' => 'Award ID',
            'award_title' => 'Award Title',
            'status' => 'Status',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['openid' => 'openid']);
    }
}
