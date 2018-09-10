<?php
namespace common\models\wechat;

use Yii;
use common\helpers\ExecuteHelper;

/**
 * This is the model class for table "{{%wechat_reply_user_api}}".
 *
 * @property int $id
 * @property int $rule_id 规则id
 * @property string $api_url 接口地址
 * @property string $description 说明
 * @property string $default 默认回复
 * @property int $cache_time 缓存时间 0默认为不缓存
 */
class ReplyUserApi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_reply_user_api}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['api_url'], 'required'],
            [['rule_id', 'cache_time'], 'integer'],
            [['api_url', 'description'], 'string', 'max' => 255],
            [['default'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => '规则ID',
            'api_url' => '接口地址',
            'description' => '备注说明',
            'default' => '默认回复文字',
            'cache_time' => '缓存时间',
        ];
    }

    /**
     * 返回自定义接口信息
     *
     * @param $model
     * @param $wechatMessage
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function getApiData($model, $wechatMessage)
    {
        $class = Yii::$app->params['userApiNamespace'] . '\\' . $model->api_url;

        // 读取接口信息
        if ($model->cache_time > 0)
        {
            $content = isset($wechatMessage['Content']) ? $wechatMessage['Content'] : '';
            // 尝试从缓存中取回 $data
            $key = Yii::$app->params['userApiCachePrefixKey'] . $model->api_url . $content;
            if (!($data = Yii::$app->cache->get($key)))
            {
                $data = ExecuteHelper::map($class, 'run', $wechatMessage);
                Yii::$app->cache->set($key, $data, $model->cache_time);
            }

            return $data;
        }

        return ExecuteHelper::map($class, 'run', $wechatMessage);
    }

    /**
     * 获取本地的api文件列表
     *
     * @return array
     */
    public static function getList()
    {
        $api_dir = Yii::$app->params['userApiPath'];
        // 获取api列表
        $dirs = array_map('basename', glob($api_dir . '/*'));
        $list = [];
        foreach ($dirs as $dir)
        {
            // 正则匹配文件名
            if (preg_match('/Api.(php)$/', $dir))
            {
                $list[] = $dir;
            }
        }

        $arr = [];
        foreach ($list as $value)
        {
            $key = str_replace(".php", "", $value);
            $arr[$key] = $value;
        }

        return $arr;
    }
}
