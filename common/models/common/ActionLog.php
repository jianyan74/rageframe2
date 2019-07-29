<?php

namespace common\models\common;

use common\enums\AuthEnum;
use common\behaviors\MerchantBehavior;
use common\models\sys\Manager;

/**
 * This is the model class for table "rf_sys_action_log".
 *
 * @property int $id 主键
 * @property string $merchant_id 商户id
 * @property int $user_id 执行用户id
 * @property string $behavior 行为类别
 * @property string $app_id 应用
 * @property string $method 提交类型
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 控制器方法
 * @property string $url 提交url
 * @property string $get_data get数据
 * @property string $post_data post数据
 * @property string $header_data 头数据
 * @property string $ip ip地址
 * @property string $remark 日志备注
 * @property string $country 国家
 * @property string $provinces 省
 * @property string $city 城市
 * @property string $device 设备
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class ActionLog extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_action_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['get_data', 'post_data', 'header_data'], 'safe'],
            [['behavior', 'app_id', 'module', 'controller', 'action', 'country', 'provinces', 'city'], 'string', 'max' => 50],
            [['method'], 'string', 'max' => 20],
            [['url', 'device'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 16],
            [['remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户',
            'user_id' => '用户',
            'app_id' => '应用',
            'behavior' => '行为',
            'method' => '提交方法',
            'module' => '模块',
            'controller' => '控制器',
            'action' => '方法',
            'url' => 'Url',
            'get_data' => 'Get Data',
            'post_data' => 'Post Data',
            'header_data' => 'Header Data',
            'ip' => 'ip',
            'remark' => '备注',
            'country' => '国家',
            'provinces' => '省',
            'city' => '市',
            'device' => '设备',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::class, ['id' => 'user_id']);
    }
}
