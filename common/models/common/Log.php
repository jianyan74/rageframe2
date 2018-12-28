<?php
namespace common\models\common;

use common\models\sys\Manager;
use common\models\member\MemberInfo;

/**
 * This is the model class for table "{{%api_log}}".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property string $method 提交类型
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 方法
 * @property string $url 提交url
 * @property string $get_data get数据
 * @property string $post_data post数据
 * @property string $ip ip地址
 *  * @property string $req_id 对外id
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
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
            [['member_id', 'error_code', 'status', 'ip', 'created_at', 'updated_at'], 'integer'],
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
            'member_id' => 'Member ID',
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
     * 记录用户访问日志
     *
     * @param $error_code
     * @param $error_msg
     * @param $error_data
     * @throws \yii\base\InvalidConfigException
     */
    public static function record($data)
    {
        $model = new self();
        $model->attributes = $data;
        $model->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(MemberInfo::className(), ['id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'member_id']);
    }
}
