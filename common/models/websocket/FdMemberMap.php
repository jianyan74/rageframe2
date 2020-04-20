<?php

namespace common\models\websocket;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\redis\ActiveRecord;
use yii\redis\Connection;

/**
 * Class FdMemberMap
 *
 * @property int $fd
 * @property int $member_id
 * @property int $merchant_id
 * @property string $type 用户类别
 * @property string $nickname 昵称
 * @property string $head_portrait 头像
 * @property string $mobile 手机号码
 * @property string $ip ip
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class FdMemberMap extends ActiveRecord
{
    /**
     * @return object|Connection|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('websocketRedis');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fd', 'member_id', 'merchant_id'], 'required'],
            [['fd', 'member_id', 'merchant_id', 'max_reception_num', 'now_reception_num', 'unread_num', 'gender', 'status'], 'integer'],
            [['nickname', 'head_portrait', 'qq', 'mobile', 'type', 'job_number', 'ip'], 'string'],
        ];
    }

    /**
     * 表字段
     *
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_keys($this->attributeLabels());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fd' => '客户端id',
            'job_number' => '工号',
            'unread_num' => '未读消息数量',
            'type' => '用户类型',
            'member_id' => '用户id',
            'merchant_id' => '商户id',
            'nickname' => '昵称',
            'head_portrait' => '头像',
            'gender' => '性别',
            'qq' => 'qq',
            'mobile' => '手机号',
            'ip' => 'ip地址',
            'max_reception_num' => '最大接待人数',
            'now_reception_num' => '当前接待人数',
            'residue_reception_num' => '剩余可接待人数',
            'status' => '状态',
            'created_at' => '发送时间',
            'updated_at' => '更新时间',
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