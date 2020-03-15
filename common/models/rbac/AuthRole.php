<?php

namespace common\models\rbac;

use Yii;
use common\traits\Tree;

/**
 * This is the model class for table "rf_rbac_auth_role".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户id
 * @property string $title 标题
 * @property string $app_id 应用
 * @property int $pid 上级id
 * @property int $level 级别
 * @property int $sort 排序
 * @property string $tree 树
 * @property int $is_default 是否默认角色
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class AuthRole extends \common\models\base\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rbac_auth_role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'trim'],
            [['title', 'pid'], 'required'],
            [['title'], 'isUniquTitle'],
            [['merchant_id', 'pid', 'is_default', 'level', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'merchant_id' => '商户id',
            'title' => '标题',
            'app_id' => '应用',
            'pid' => '上级角色',
            'level' => '级别',
            'sort' => '排序',
            'tree' => '树',
            'is_default' => '默认',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param $attribute
     */
    public function isUniquTitle($attribute)
    {
        $merchant_id = $this->merchant_id;
        !$merchant_id && $merchant_id = Yii::$app->services->merchant->getId();

        $model = self::find()->where([
            'merchant_id' => $merchant_id,
            'title' => $this->title
        ])->one();

        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '角色名称已存在');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChild()
    {
        return $this->hasMany(AuthItemChild::class, ['role_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        AuthItemChild::deleteAll(['role_id' => $this->id]);
        AuthAssignment::deleteAll(['role_id' => $this->id]);

        $this->autoDeleteTree();

        return parent::beforeDelete();
    }
}
