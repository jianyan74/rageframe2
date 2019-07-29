<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_provinces}}".
 *
 * @property int $id ID
 * @property string $title 栏目名
 * @property int $pid 父栏目
 * @property string $short_title 缩写
 * @property int $areacode 区域编码
 * @property int $zipcode 邮政编码
 * @property string $pinyin 拼音
 * @property string $lng 经度
 * @property string $lat 纬度
 * @property int $level 级别
 * @property string $tree
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
            [['id', 'tree'], 'required'],
            [['id', 'pid', 'areacode', 'zipcode', 'level', 'sort'], 'integer'],
            [['title', 'short_title'], 'string', 'max' => 50],
            [['pinyin'], 'string', 'max' => 100],
            [['lng', 'lat'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 200],
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
            'title' => '标题',
            'pid' => 'Pid',
            'short_title' => '标题缩写',
            'areacode' => '区域编码',
            'zipcode' => '邮政编码',
            'pinyin' => '拼音',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'level' => '级别',
            'tree' => '树',
            'sort' => '排序',
        ];
    }
}
