<?php
namespace backend\modules\wechat\models;

use yii\base\Model;
use common\models\wechat\Setting;

/**
 * Class HistoryForm
 * @package backend\modules\wechat\models
 */
class HistoryForm extends Model
{
    public $history_status = 1;
    public $utilization_status = 1;
    public $msg_history_date = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['history_status', 'utilization_status', 'msg_history_date'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'history_status' => '开启历史消息记录',
            'utilization_status' => '开启利用率统计',
            'msg_history_date' => '历史消息记录天数',
        ];
    }
}
