<?php
namespace common\models\sys;

use Yii;
use yii\rbac\Rule;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sys_auth_rule}}".
 *
 * @property string $name
 * @property string $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthItem[] $sysAuthItems
 */
class AuthRule extends \common\models\common\BaseModel
{
    /**
     * RBAC规则类名
     *
     * @var
     */
    public $className;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_auth_rule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'className'], 'required'],
            [['name'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['className'], 'classExists']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => '规则名称',
            'className' => '规则类名',
            'data' => 'Data',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 验证类名称是否符合规则
     */
    public function classExists()
    {
        if (!class_exists($this->className))
        {
            $message = "没有发找到类'{$this->className}'";
            $this->addError('className', $message);
        }

        if (!is_subclass_of($this->className, Rule::className()))
        {
            $message = "'{$this->className}'必须是 RBAC 规则";
            $this->addError('className', $message);
        }
    }

    /**
     * @param $data
     * @return bool|string
     */
    public static function getClassName($data)
    {
        if (!empty($data))
        {
            $data = unserialize($data);
            return get_class($data);
        }

        return false;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'name', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $rule = new $this->className;
        $this->data = serialize($rule);

        return parent::beforeSave($insert);
    }
}
