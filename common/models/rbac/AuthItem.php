<?php

namespace common\models\rbac;

use common\traits\Tree;

/**
 * This is the model class for table "{{%rbac_auth_item}}".
 *
 * @property int $id
 * @property string $name 别名
 * @property string $title 标题
 * @property string $app_id 应用
 * @property string $addons_name 插件名称
 * @property int $pid 父级id
 * @property int $level 级别
 * @property int $is_addon 是否插件
 * @property int $sort 排序
 * @property string $tree 树
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at
 * @property int $updated_at
 */
class AuthItem extends \common\models\base\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rbac_auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name'], 'trim'],
            [['title', 'name'], 'required'],
            [['name'], 'uniquName', 'on' => 'default'],
            [['pid', 'level', 'is_addon', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['title', 'addons_name'], 'string', 'max' => 200],
            [['app_id'], 'string', 'max' => 20],
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
            'name' => '别名',
            'title' => '标题',
            'app_id' => '应用',
            'addons_name' => '插件名称',
            'pid' => '父级id',
            'level' => '级别',
            'is_addon' => '是否插件',
            'sort' => '排序',
            'tree' => '树',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 场景
     *
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['addonsBatchCreate'] = array_keys($this->attributeLabels());

        return $scenarios;
    }

    /**
     * @param $attribute
     */
    public function uniquName($attribute)
    {
        $model = self::find()
            ->where(['name' => $this->name, 'app_id' => $this->app_id])
            ->andFilterWhere(['addons_name' => $this->addons_name])
            ->one();

        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '别名已存在');
        }
    }

    /**
     * @param AuthItem $parent
     */
    public function setParent(AuthItem $parent)
    {
        $this->parent = $parent;
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
}
