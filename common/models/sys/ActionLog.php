<?php
namespace common\models\sys;

use Yii;
use Zhuzhichao\IpLocationZh\Ip;

/**
 * This is the model class for table "{{%sys_action_log}}".
 *
 * @property int $id 主键
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
 * @property string $area 地区
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class ActionLog extends \common\models\common\BaseModel
{
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
            [['manager_id', 'status', 'ip', 'created_at', 'updated_at'], 'integer'],
            [['get_data', 'post_data'], 'string'],
            [['behavior', 'module', 'controller', 'action', 'country', 'provinces', 'city'], 'string', 'max' => 50],
            [['method'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 1000],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manager_id' => 'Manager ID',
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
     * 记录用户行为日志
     *
     * @param $behavior
     * @param $remark
     * @param $noRecordData
     * @throws \yii\base\InvalidConfigException
     */
    public static function record($behavior, $remark, $noRecordData)
    {
        $url = Yii::$app->request->getUrl();
        $url = explode('?', $url);

        $model = new self();
        $model->manager_id = Yii::$app->user->id ?? 0;
        $model->behavior = $behavior;
        $model->remark = $remark;
        $model->url = $url[0];
        $model->get_data = json_encode(Yii::$app->request->get());
        $model->post_data = $noRecordData == true ? json_encode(Yii::$app->request->post()) : json_encode([]);
        $model->method = Yii::$app->request->method;
        $model->module = Yii::$app->controller->module->id;
        $model->controller = Yii::$app->controller->id;
        $model->action = Yii::$app->controller->action->id;
        $model->ip = Yii::$app->request->userIP;

        // ip转地区
        if (!empty($model->ip) && ($ipData = Ip::find($model->ip)))
        {
            $model->country = $ipData[0];
            $model->provinces = $ipData[1];
            $model->city = $ipData[2];
        }

        $model->ip = ip2long($model->ip);
        $model->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'manager_id']);
    }
}
