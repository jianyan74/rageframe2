<?php
namespace common\models\common;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * AR model基类
 *
 * Class BaseModel
 * @package common\models\common
 */
class BaseModel extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
