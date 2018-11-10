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
     * 获取首个报错内容
     *
     * @return bool
     */
    public function getFirstErrorMessage()
    {
        $firstErrors = $this->getFirstErrors();
        if (!is_array($firstErrors) || empty($firstErrors))
        {
            return false;
        }

        $errors = array_values($firstErrors)[0];
        return $errors ?? false;
    }

    /**
     * 软删除
     *
     * @param $id
     * @return bool
     */
    public static function destroy($id)
    {
        if ($model = self::findOne($id))
        {
            $model->status = StatusEnum::DELETE;
            return $model->save();
        }

        return false;
    }

    /**
     * 查询 model 基于 id
     *
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findById($id)
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
