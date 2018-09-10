<?php
namespace common\models\wechat;

use Yii;
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
            'pid' => 'Pid',
            'level' => '级别',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $pid
     * @return MenuProvinces[]
     */
    public static function getFindPidList($pid)
    {
        return self::find()->where(['pid' => $pid])->orderBy('id asc')->all();
    }

    /**
     * 根据父级ID返回信息
     *
     * @param int $parentid
     * @return array
     */
    public static function getMenuList($pid = 0)
    {
        return ArrayHelper::map(self::getFindPidList($pid), 'title', 'title');
    }

    /**
     * 根据父级标题返回信息
     *
     * @param int $parentid
     * @return array
     */
    public static function getMenuTitle($title)
    {
        if($model = self::findOne(['title' => $title, 'level' => 2]))
        {
            return self::getMenuList($model->id);
        }

        return [];
    }
}
