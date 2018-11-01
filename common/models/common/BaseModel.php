<?php
namespace common\models\common;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\enums\StatusEnum;

/**
 * AR model基类
 *
 * Class BaseModel
 * @package common\models\common
 */
class BaseModel extends ActiveRecord
{
    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findId($id)
    {
        return self::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->one();
    }

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
