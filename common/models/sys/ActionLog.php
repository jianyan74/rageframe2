<?php
namespace common\models\sys;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "rf_sys_action_log".
 *
 * @property int $id 主键
 * @property string $merchant_id 商户id
 * @property int $manager_id 执行用户id
 * @property string $behavior 行为类别
 * @property string $method 提交类型
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 控制器方法
 * @property string $url 提交url
 * @property string $get_data get数据
 * @property string $post_data post数据
 * @property string $ip ip地址
 * @property string $remark 日志备注
 * @property string $country 国家
 * @property string $provinces 省
 * @property string $city 城市
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class ActionLog extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_action_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'manager_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['get_data', 'post_data'], 'string'],
            [['behavior', 'module', 'controller', 'action', 'country', 'provinces', 'city'], 'string', 'max' => 50],
            [['method'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 200],
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
            'manager_id' => '用户',
            'behavior' => '行为',
            'method' => '提交方法',
            'module' => '模块',
            'controller' => '控制器',
            'action' => '方法',
            'url' => 'Url',
            'get_data' => 'Get Data',
            'post_data' => 'Post Data',
            'ip' => 'Ip地址',
            'remark' => '备注',
            'country' => '国家',
            'provinces' => '省',
            'city' => '市',
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
        return $this->hasOne(Manager::class, ['id' => 'manager_id']);
    }
}
