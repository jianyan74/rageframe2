<?php

namespace addons\Wechat\services;

use Yii;
use yii\helpers\Json;
use common\components\Service;
use common\helpers\ExecuteHelper;

/**
 * Class RuleService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class RuleService extends Service
{
    /**
     * 返回自定义接口信息
     *
     * @param $model
     * @param $wechatMessage
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function getApiData($model, $wechatMessage)
    {
        $modelData = Json::decode($model->data);
        $class = Yii::$app->params['userApiNamespace'] . '\\' . $modelData['api_url'];

        // 读取接口信息
        if ($modelData['cache_time'] > 0) {
            $content = isset($wechatMessage['Content']) ? $wechatMessage['Content'] : '';
            // 尝试从缓存中取回 $data
            $key = Yii::$app->params['userApiCachePrefixKey'] . $modelData['api_url'] . $content;
            if (!($data = Yii::$app->cache->get($key))) {
                $data = ExecuteHelper::map($class, 'run', $wechatMessage);
                Yii::$app->cache->set($key, $data, $modelData['cache_time']);
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
    public function getApiList()
    {
        $api_dir = Yii::$app->params['userApiPath'];
        // 获取api列表
        $dirs = array_map('basename', glob($api_dir . '/*'));
        $list = [];
        foreach ($dirs as $dir) {
            // 正则匹配文件名
            if (preg_match('/Api.(php)$/', $dir)) {
                $list[] = $dir;
            }
        }

        $arr = [];
        foreach ($list as $value) {
            $key = str_replace(".php", "", $value);
            $arr[$key] = $value;
        }

        return $arr;
    }
}