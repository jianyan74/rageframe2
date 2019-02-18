<?php
namespace backend\modules\sys\models;

use common\models\sys\Notify;

/**
 * Class NotifyAnnounceForm
 * @package backend\modules\sys\models
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyAnnounceForm extends Notify
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 150],
        ];
    }
}