<?php
namespace common\models\sys;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%sys_auth_assignment}}".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_auth_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at', 'user_id'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => '角色名称',
            'user_id' => 'User ID',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function finldByUserId($id)
    {
        return self::find()
            ->where(['user_id' => $id])
            ->with(['authItemChild', 'addonsAuthItemChild'])
            ->asArray()
            ->one();
    }

    /**
     * @param $itemNames
     * @return array|null|ActiveRecord
     */
    public static function finldItemNames($itemNames)
    {
        return self::find()
            ->where(['in', 'item_name', $itemNames])
            ->select('user_id')
            ->asArray()
            ->one();
    }

    /**
     * 根据用户ID获取权限名称
     * @param $user_id
     * @return bool|mixed
     */
    public function getName($user_id)
    {
        if(!$user_id)
        {
            return false;
        }

        $model = $this::find()
            ->where(['user_id' => $user_id])
            ->one();

        return $model ? $model->item_name : false ;
    }

    /**
     * 关联权限名称
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }

    /**
     * 关联权限列表
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChild()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'item_name']);
    }

    /**
     * 关联插件模块权限列表
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddonsAuthItemChild()
    {
        return $this->hasMany(AddonsAuthItemChild::className(), ['parent' => 'item_name']);
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }
}
