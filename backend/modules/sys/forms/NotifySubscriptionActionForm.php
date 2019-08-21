<?php

namespace backend\modules\sys\forms;

use yii\base\Model;

/**
 * Class NotifySubscriptionActionForm
 * @package backend\modules\sys\forms
 * @author jianyan74 <751393839@qq.com>
 */
class NotifySubscriptionActionForm extends Model
{
    public $log_warning = 0;
    public $log_error = 0;
    public $behavior_warning = 0;
    public $behavior_error = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_warning', 'log_error'], 'integer'],
            [['behavior_warning', 'behavior_error'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'behavior_warning' => '警告行为',
            'behavior_error' => '错误行为',
            'log_warning' => '警告请求日志',
            'log_error' => '错误请求日志',
        ];
    }
}
