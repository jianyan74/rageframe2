<?php
namespace common\models\common;

use Yii;
use common\behaviors\MerchantBehavior;
use common\helpers\TreeHelper;
use backend\components\Tree;

/**
 * This is the model class for table "{{%common_auth_role}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $type 类别
 * @property string $pid 上级id
 * @property int $level 级别
 * @property int $sort 排序
 * @property string $tree 树
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 添加时间
 * @property string $updated_at 修改时间
 */
class AuthRole extends \common\models\common\BaseModel
{
    use Tree, MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_auth_role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'pid'], 'required'],
            [['title'], 'uniquTitle'],
            [['merchant_id', 'pid', 'level', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '角色名称',
            'type' => '类型',
            'pid' => '父级',
            'level' => '级别',
            'sort' => '排序',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param $attribute
     */
    public function uniquTitle($attribute)
    {
        $model = self::find()->where(['merchant_id' => Yii::$app->services->merchant->getId(), 'title' => $this->title])->one();
        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '角色名称已存在');
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
     * @return bool
     */
    public function beforeDelete()
    {
        AuthItemChild::deleteAll(['role_id' => $this->id]);
        AuthAssignment::deleteAll(['role_id' => $this->id]);
        self::deleteAll(['like', 'tree', $this->tree . TreeHelper::prefixTreeKey($this->id) . '%', false]);

        return parent::beforeDelete();
    }
}