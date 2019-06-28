<?php
namespace services\sys;

use Yii;
use yii\helpers\Json;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\sys\ActionLog;
use Zhuzhichao\IpLocationZh\Ip;

/**
 * Class ActionLogService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class ActionLogService extends Service
{
    /**
     * @param $manager_id
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByManagerId($manager_id, $limit = 12)
    {
        return ActionLog::find()
            ->where(['manager_id' => $manager_id, 'status' => StatusEnum::ENABLED])
            ->andWhere(['in', 'behavior', ['login', 'logout']])
            ->limit($limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
    }

    /**
     * 行为日志
     *
     * @param string $behavior 行为
     * @param string $remark 备注
     * @param bool $noRecordData 是否记录post数据
     * @throws \yii\base\InvalidConfigException
     */
    public function create($behavior, $remark, $noRecordData = true)
    {
        $url = Yii::$app->request->getUrl();
        $url = explode('?', $url);
        $ip = Yii::$app->request->userIP;

        $model = new ActionLog();
        $model->manager_id = Yii::$app->user->id ?? 0;
        $model->behavior = $behavior;
        $model->remark = $remark;
        $model->url = $url[0];
        $model->get_data = Json::encode(Yii::$app->request->get());
        $model->post_data = $noRecordData == true ? Json::encode(Yii::$app->request->post()) : Json::encode([]);
        $model->method = Yii::$app->request->method;
        $model->module = Yii::$app->controller->module->id;
        $model->controller = Yii::$app->controller->id;
        $model->action = Yii::$app->controller->action->id;
        $model->ip = ip2long($ip);
        $model->ip = (string) $model->ip;
        // ip转地区
        if (!empty($ip) && ip2long($ip) && ($ipData = Ip::find($ip))) {
            $model->country = $ipData[0];
            $model->provinces = $ipData[1];
            $model->city = $ipData[2];
        }

        $model->save();
    }
}