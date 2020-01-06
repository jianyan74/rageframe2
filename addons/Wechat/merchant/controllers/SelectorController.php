<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use yii\helpers\Json;
use common\helpers\ArrayHelper;
use addons\Wechat\common\models\Attachment;
use common\helpers\ResultHelper;

/**
 * 微信资源选择器
 *
 * Class SelectorController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SelectorController extends BaseController
{
    /**
     * 获取图片/视频/音频/图文
     *
     * @param bool $json 返回json格式
     * @return array|string
     */
    public function actionList($json = false)
    {
        $keyword = Yii::$app->request->get('keyword');
        $media_type = Yii::$app->request->get('media_type');
        $year = Yii::$app->request->get('year', '');
        $month = Yii::$app->request->get('month', '');

        if ($media_type == Attachment::TYPE_NEWS) {
            $models = Yii::$app->wechatService->attachmentNews->getFirstListPage($year, $month, $keyword);
        } else {
            $models = Yii::$app->wechatService->attachment->getListPage($media_type, $year, $month, $keyword);
        }

        if ($json == true) {
            return ResultHelper::json(200, '获取成功', $models);
        }

        return $this->renderAjax('selector', [
            'models' => Json::encode($models),
            'media_type' => $media_type,
            'boxId' => Yii::$app->request->get('boxId'),
            'year' => ArrayHelper::numBetween(2014, date('Y')),
            'month' => ArrayHelper::numBetween(1, 12),
        ]);
    }
}