<?php

namespace common\traits;

use Yii;
use yii\web\Response;
use common\enums\StatusEnum;
use common\models\member\Member;

/**
 * Trait MemberSelectAction
 * @package common\traits
 */
trait MemberSelectAction
{
    /**
     * select2 查询
     *
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionSelect2($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [
            'results' => [
                'id' => '',
                'text' => ''
            ]
        ];

        if (!is_null($q)) {
            $data = Member::find()
                ->select('id, mobile as text')
                ->where(['like', 'mobile', $q])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->limit(10)
                ->asArray()
                ->all();

            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Member::findOne($id)->mobile];
        }

        return $out;
    }
}