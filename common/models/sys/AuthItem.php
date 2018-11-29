<?php

namespace common\models\sys;

use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sys_auth_item}}".
 *
 * @property string $name
 * @property int $type
 * @property int $key 唯一key
 * @property string $description
 * @property string $rule_name 规则名称
 * @property string $data
 * @property int $parent_key 父级key
 * @property int $level 级别
 * @property int $sort 排序
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $AuthAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $AuthItemChildren
 * @property AuthItemChild[] $AuthItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends \common\models\common\BaseModel
{
    /**
     * 角色
     */
    const ROLE = 1;
    /**
     * 权限
     */
    const AUTH = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['name'], 'required', 'message' => '内容不能为空'],
            [['type', 'level', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['key','parent_key'], 'safe'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            ['name', 'unique', 'message' => '名称已存在,请重新输入'],
            ['parent_key', 'default', 'value' => 0],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => '路由地址',
            'type' => '类型',
            'key' => 'Key',
            'description' => '路由说明',
            'rule_name' => '规则',
            'data' => 'Data',
            'parent_key' => 'Parent Key',
            'level' => '级别',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('{{%sys_auth_item_child}}', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('{{%sys_auth_item_child}}', ['child' => 'name']);
    }

    /**
     * 删除子权限
     *
     * @return bool
     */
    public function beforeDelete()
    {
        $data = self::find()->asArray()->all();
        $keys = ArrayHelper::getChildsId($data, $this->key, 'key', 'parent_key');

        self::deleteAll(['in', 'key', $keys]);

        return parent::beforeDelete();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
        {
            // 设置key
            $model = self::find()
                ->orderBy('key desc')
                ->select('key')
                ->one();

            $key = $model['key'];
            $this->key = $key ? $key + 1 : 1;
        }

        if (empty($this->rule_name))
        {
            $this->rule_name = null;
        }

        return parent::beforeSave($insert);
    }
}
