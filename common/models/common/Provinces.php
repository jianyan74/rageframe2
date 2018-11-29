<?php
namespace common\models\common;

use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%common_provinces}}".
 *
 * @property int $id ID
 * @property string $title 栏目名
 * @property int $pid 父栏目
 * @property string $shortname 缩写
 * @property int $areacode 区域编码
 * @property int $zipcode 邮政编码
 * @property string $pinyin 拼音
 * @property string $lng 经度
 * @property string $lat 纬度
 * @property int $level 级别
 * @property string $position
 * @property int $sort 排序
 */
class Provinces extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_provinces}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'position'], 'required'],
            [['id', 'pid', 'areacode', 'zipcode', 'level', 'sort'], 'integer'],
            [['title', 'shortname'], 'string', 'max' => 50],
            [['pinyin'], 'string', 'max' => 100],
            [['lng', 'lat'], 'string', 'max' => 20],
            [['position'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '名称',
            'pid' => 'pid',
            'short_title' => '简称',
            'areacode' => 'Areacode',
            'zipcode' => 'Zipcode',
            'pinyin' => '拼音',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'level' => '级别',
            'position' => 'Position',
            'sort' => '排序',
        ];
    }
    /**
     * 根据父级ID返回信息
     *
     * @param int $pid
     * @return array
     */
    public static function getCityList($pid = 0, $is_child = false)
    {
        $data = Provinces::find()->where(['pid' => $pid]);
        $is_child && $data = $data->andWhere(['>', 'pid', 0]);
        $model = $data->all();

        return ArrayHelper::map($model, 'id', 'title');
    }

    /**
     * 根据id获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public static function getCityName($id)
    {
        if ($provinces = Provinces::findOne($id))
        {
            return $provinces['title'];
        }

        return false;
    }

    /**
     * 根据id数组获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public static function getCityListName(array $ids)
    {
        if($provinces =  Provinces::find()->where(['in', 'id', $ids])->all())
        {
            $address = '';
            foreach ($provinces as $province)
            {
                $address .= $province['title'] . ' ';
            }

            return $address;
        }

        return false;
    }
}
