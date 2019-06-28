<?php
namespace common\models\wechat;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%wechat_fans_stat}}".
 *
 * @property string $id
 * @property int $new_attention 今日新关注
 * @property int $cancel_attention 今日取消关注
 * @property int $cumulate_attention 累计关注
 * @property string $date
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 添加时间
 * @property string $updated_at 修改时间
 */
class FansStat extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_fans_stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'new_attention', 'cancel_attention', 'cumulate_attention', 'status', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'new_attention' => '今日新关注',
            'cancel_attention' => '今日取消关注',
            'cumulate_attention' => '累计关注',
            'date' => '日期',
            'status' => 'Status',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 行为
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['merchant_id'],
                ],
                'value' => Yii::$app->services->merchant->getId(),
            ]
        ];
    }
}
