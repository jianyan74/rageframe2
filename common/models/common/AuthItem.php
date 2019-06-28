<?php
namespace common\models\common;

use backend\components\Tree;

/**
 * This is the model class for table "{{%common_auth_item}}".
 *
 * @property int $id
 * @property string $name 别名
 * @property string $type 类别
 * @property string $type_child 子类别
 * @property string $addons_name 插件名称
 * @property string $title 说明
 * @property int $pid 父级id
 * @property int $is_menu 是否菜单
 * @property int $level 级别
 * @property int $sort 排序
 * @property string $tree 树
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at
 * @property int $updated_at
 */
class AuthItem extends \common\models\common\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name'], 'required'],
            [['name'], 'uniquName'],
            [['is_menu', 'pid', 'level', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['type', 'type_child'], 'string', 'max' => 20],
            [['addons_name'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 200],
            [['tree'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '说明',
            'type' => '类型',
            'type_child' => '子类型',
            'addons_name' => '插件名称',
            'name' => '路由/别名',
            'pid' => '父类',
            'level' => '级别',
            'sort' => '排序',
            'tree' => '树',
            'is_menu' => '是否菜单',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $attribute
     */
    public function uniquName($attribute)
    {
        $model = self::find()->where(['name' => $this->name, 'type' => $this->type])->andFilterWhere(['addons_name' => $this->addons_name])->one();
        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '已存在');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->isNewRecord) {
            AuthItemChild::updateAll(['name' => $this->name], ['item_id' => $this->id]);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        AuthItemChild::deleteAll(['item_id' => $this->id]);
        parent::afterDelete();
    }
}
