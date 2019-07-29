<?php
namespace addons\RfSignShoppingDay\common\models;

use common\behaviors\MerchantBehavior;
use common\helpers\StringHelper;
use Yii;

/**
 * This is the model class for table "{{%addon_RfSign_shopping_street_award}}".
 *
 * @property int $id
 * @property string $title 奖品名称
 * @property int $sort 排序
 * @property int $prob 中奖概率
 * @property int $all_num 奖品总数量
 * @property int $surplus_num 奖品剩余数量
 * @property int $max_day_num 每日限制中奖数
 * @property int $max_user_num 每人最多中奖数
 * @property int $start_time 奖品有效开始时间
 * @property int $end_time 奖品有效结束时间
 * @property int $draw_start_time 奖品可中开始时间
 * @property int $draw_end_time 奖品可中结束时间
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Award extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_sign_shopping_street_award}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'prob'], 'required'],
            [['merchant_id', 'cate_id', 'sort', 'prob', 'all_num', 'surplus_num', 'max_day_num', 'max_user_num',  'status', 'created_at', 'updated_at'], 'integer'],
            [['start_time', 'end_time', 'draw_start_time', 'draw_end_time'], 'safe'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'sort' => '排序',
            'prob' => '中奖几率',
            'all_num' => '总数量',
            'surplus_num' => '剩余数量',
            'max_day_num' => '单日最多中奖数量',
            'max_user_num' => '每个用户最多中奖数量',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'draw_start_time' => '中奖开始时间',
            'draw_end_time' => '中奖结束时间',
            'cate_id' => '分类',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->start_time = StringHelper::dateToInt($this->start_time);
        $this->end_time = StringHelper::dateToInt($this->end_time);
        $this->draw_start_time = StringHelper::dateToInt($this->draw_start_time);
        $this->draw_end_time = StringHelper::dateToInt($this->draw_end_time);

        return parent::beforeSave($insert);
    }
}
