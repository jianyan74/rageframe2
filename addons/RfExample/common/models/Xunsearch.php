<?php
namespace addons\RfExample\common\models;

use Yii;
use hightman\xunsearch\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Xunsearch
 * @package addons\RfExample\common\models
 */
class Xunsearch extends ActiveRecord
{
    public static function projectName()
    {
        return 'demo';	// 这将使用 @common/config/demo.ini 作为项目名
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['title'], 'required'],
            [['title', 'author'], 'string', 'max' => 50],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'author' => '作者',
            'content' => '内容',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
