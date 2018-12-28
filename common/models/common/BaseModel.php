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
     * 获取查询数据
     *
     * @param array $where 条件
     * @param array $fields 字段
     * @param string $orderby 排序
     * @param array $with 关联
     * @return array|ActiveRecord[]
     */
    public static function getMultiDate($where = [], $fields = ['id'], $orderby = 'id asc', $with = [])
    {
        return self::find()
            ->where($where)
            ->select($fields)
            ->with($with)
            ->orderby($orderby)
            ->asArray()
            ->all();
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
