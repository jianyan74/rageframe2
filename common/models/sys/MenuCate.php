<?php
namespace common\models\sys;

use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%sys_menu_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $icon icon
 * @property int $is_default_show 默认显示
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 添加时间
 * @property string $updated_at 修改时间
 */
class MenuCate extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_menu_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_default_show', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['icon'], 'string', 'max' => 20],
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
            'icon' => '图标css',
            'is_default_show' => '默认显示',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList()
    {
        return self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc')
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getFirstDataID()
    {
        $model = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->select('id, status')
            ->orderBy('sort asc')
            ->one();

        return $model ? $model->id : null;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->is_default_show == StatusEnum::ENABLED)
        {
            self::updateAll(['is_default_show' => StatusEnum::DISABLED], ['is_default_show' => StatusEnum::ENABLED]);
        }

        return parent::beforeSave($insert);
    }
}
