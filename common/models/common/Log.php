<?php

namespace common\models\common;

use common\models\member\Member;

/**
 * This is the model class for table "{{%common_log}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property int $user_id 用户id
 * @property string $method 提交类型
 * @property string $app_id 应用id
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 方法
 * @property string $url 提交url
 * @property string $get_data get数据
 * @property string $post_data post数据
 * @property string $header_data
 * @property string $ip ip地址
 * @property int $error_code 报错code
 * @property int $user_agent
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property string $req_id 对外id
 * @property string $device 设备
 * @property string $device_uuid 设备唯一号
 * @property string $device_version 设备版本
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Log extends \common\models\base\BaseModel
{
    const DEVICE_IOS = 'ios';
    const DEVICE_ANDROID = 'android';
    const DEVICE_OTHER = 'other';

    /**
     * @var array
     */
    public static $deviceExplain = [
        self::DEVICE_IOS => '苹果系统',
        self::DEVICE_ANDROID => '安卓系统',
        self::DEVICE_OTHER => '其他',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'user_id', 'error_code', 'status', 'created_at', 'updated_at', 'ip'], 'integer'],
            [['get_data', 'post_data', 'error_data', 'header_data'], 'safe'],
            [['method'], 'string', 'max' => 20],
            [['module', 'action', 'req_id', 'app_id'], 'string', 'max' => 50],
            [['controller'], 'string', 'max' => 100],
            [['user_agent'], 'string', 'max' => 200],
            [['url', 'error_msg'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'user_id' => '用户id',
            'app_id' => '应用id',
            'method' => '提交方法',
            'module' => '模块',
            'controller' => '控制器',
            'action' => '方法',
            'url' => '访问链接',
            'get_data' => 'Get 数据',
            'post_data' => 'Post 数据',
            'header_data' => 'Header 数据',
            'ip' => 'ip',
            'req_id' => '对外id',
            'error_code' => '报错编码',
            'error_msg' => '报错信息',
            'error_data' => '报错内容',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBackendMember()
    {
        return $this->hasOne(\common\models\backend\Member::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchantMember()
    {
        return $this->hasOne(\common\models\merchant\Member::class, ['id' => 'user_id']);
    }
}
