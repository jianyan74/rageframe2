<?php

namespace addons\Wechat\services;

use Yii;
use common\components\Service;
use addons\Wechat\common\models\Qrcode;

/**
 * Class QrcodeService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class QrcodeService extends Service
{
    /**
     * @param array $where
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByWhere(array $where = [])
    {
        return Qrcode::find()
            ->filterWhere($where)
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('created_at desc')
            ->one();
    }

    /**
     * 返回场景ID
     *
     * @return int|mixed
     */
    public function getSceneId()
    {
        $qrCode = Qrcode::find()
            ->where(['model' => Qrcode::MODEL_TEM])
            ->filterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('created_at desc')
            ->one();

        return $qrCode ? $qrCode->scene_id + 1 : 10001;
    }

    /**
     * @param Qrcode $model
     */
    public function syncCreate(Qrcode $model)
    {
        $qrcode = Yii::$app->wechat->app->qrcode;
        if ($model->model == Qrcode::MODEL_TEM) {
            $scene_id = $this->getSceneId();
            $result = $qrcode->temporary($scene_id, $model->expire_seconds);
            $model->scene_id = $scene_id;
            $model->expire_seconds = $result['expire_seconds']; // 有效秒数
        } else {
            $result = $qrcode->forever($model->scene_str);
        }

        $model->ticket = $result['ticket'];
        $model->type = 'scene';
        $model->url = $result['url']; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
        return $model;
    }
}