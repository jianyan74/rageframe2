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
    public $sys;
    public $dingtalk;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sys', 'dingtalk'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sys' => '系统',
            'dingtalk' => '钉钉',
        ];
    }
}
