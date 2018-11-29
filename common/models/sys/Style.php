<?php
namespace common\models\sys;

use Yii;

/**
 * This is the model class for table "{{%sys_style}}".
 *
 * @property int $id
 * @property int $manager_id 管理员id
 * @property int $skin_id 皮肤
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Style extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_style}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['skin_id', 'in', 'range' => [0, 1 ,3]],
            [['manager_id', 'skin_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manager_id' => '管理员ID',
            'skin_id' => '皮肤id',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 获取样式
     *
     * @param $manager_id
     * @return array|bool|Style|null|\yii\db\ActiveRecord
     */
    public static function findByManagerId($manager_id)
    {
        $model = self::find()
            ->where(['manager_id' => $manager_id])
            ->one();

        if (!$model)
        {
            $model = new self();
            $model->manager_id = $manager_id;
            $model->save();
        }

        return $model;
    }
}
