<?php
namespace common\models\wechat;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%wechat_menu_provinces}}".
 *
 * @property int $id
 * @property string $title 栏目名
 * @property int $pid 父栏目
 * @property int $level 级别
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class MenuProvinces extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_menu_provinces}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'level', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
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
            'pid' => '父级id',
            'level' => '级别',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     *
     * @param $pid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findListByPid($pid)
    {
        return self::find()
            ->where(['pid' => $pid, 'status' => StatusEnum::ENABLED])
            ->orderBy('id asc')
            ->all();
    }

    /**
     * 根据父级ID返回信息
     *
     * @param int $parentid
     * @return array
     */
    public static function getMenuList($pid = 0)
    {
        return ArrayHelper::map(self::findListByPid($pid), 'title', 'title');
    }

    /**
     * 根据父级标题返回信息
     *
     * @param int $parentid
     * @return array
     */
    public static function getMenuTitle($title)
    {
        if($model = self::findOne(['title' => $title, 'level' => 2, 'status' => StatusEnum::ENABLED]))
        {
            return self::getMenuList($model->id);
        }

        return [];
    }
}
