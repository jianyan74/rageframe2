<?php
namespace common\services\sys;

use Yii;
use common\services\Service;
use common\models\sys\ActionLog;
use Zhuzhichao\IpLocationZh\Ip;

/**
 * Class Sys
 * @package common\services\sys
 * @property \common\services\sys\Auth $auth
 * @property \common\services\sys\Addon $addon
 * @author jianyan74 <751393839@qq.com>
 */
class Sys extends Service
{
    /**
     * 是否超级管理员
     *
     * @return bool
     */
    public function isAuperAdmin()
    {
        return Yii::$app->user->id == Yii::$app->params['adminAccount'];
    }

    /**
     * 行为日志
     *
     * @param string $behavior 行为
     * @param string $remark 备注
     * @param bool $noRecordData 是否记录post数据
     * @throws \yii\base\InvalidConfigException
     */
    public function log($behavior, $remark, $noRecordData = true)
    {
        $url = Yii::$app->request->getUrl();
        $url = explode('?', $url);

        $model = new ActionLog();
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
        if (!empty($model->ip) && ip2long($model->ip) && ($ipData = Ip::find($model->ip)))
        {
            $model->country = $ipData[0];
            $model->provinces = $ipData[1];
            $model->city = $ipData[2];
        }

        $model->ip = ip2long($model->ip);
        $model->save();
    }
}