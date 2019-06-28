<?php
namespace common\models\common;

use common\behaviors\MerchantBehavior;
use common\models\member\Member;
use common\models\sys\Manager;

/**
 * This is the model class for table "{{%common_log}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property int $user_id 用户id
 * @property int $group 组别[1:会员;2:后台管理员]
 * @property string $method 提交类型
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 方法
 * @property string $url 提交url
 * @property string $get_data get数据
 * @property string $post_data post数据
 * @property string $ip ip地址
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property string $req_id 对外id
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Log extends \common\models\common\BaseModel
{
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
            [['merchant_id', 'user_id', 'group', 'error_code', 'status', 'created_at', 'updated_at', 'ip'], 'integer'],
            [['get_data', 'post_data', 'error_data'], 'string'],
            [['method'], 'string', 'max' => 20],
            [['module', 'controller', 'action', 'req_id'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 1000],
            [['error_msg'], 'string', 'max' => 200],
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
            'group' => '用户组别',
            'method' => '提交方法',
            'module' => '模块',
            'controller' => '控制器',
            'action' => '方法',
            'url' => '访问链接',
            'get_data' => 'Get 数据',
            'post_data' => 'Post 数据',
            'ip' => 'Ip地址',
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
    public function getManager()
    {
        return $this->hasOne(Manager::class, ['id' => 'user_id']);
    }
}
