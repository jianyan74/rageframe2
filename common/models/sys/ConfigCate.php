<?php
namespace common\models\sys;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sys_config_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property int $pid 上级id
 * @property int $level 级别
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class ConfigCate extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_config_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'level', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'pid' => '上级id',
            'level' => '级别',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 获取递归列表
     *
     * @return array
     */
    public static function getItemsMergeList()
    {
        $models = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->with(['config'])
            ->orderBy('sort asc,id desc')
            ->asArray()
            ->all();

        return ArrayHelper::itemsMerge($models);
    }

    /**
     * 获取下拉文本框列表
     *
     * @return array
     */
    public static function getDropDownList()
    {
        $models = static::getItemsMergeList();
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    /**
     * 关联配置
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasMany(Config::className(), ['cate_id' => 'id'])
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc, id desc');
    }
}
