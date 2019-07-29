<?php

namespace backend\widgets\cropper;

use Yii;
use yii\web\Controller;

/**
 * Class CropperController
 * @package backend\widgets\cropper
 * @author jianyan74 <751393839@qq.com>
 */
class CropperController extends Controller
{
    /**
     * @return string
     */
    public function actionCrop()
    {
        return $this->renderAjax('@backend/widgets/cropper/views/crop', [
            'boxId' => Yii::$app->request->get('boxId'),
            'multiple' => Yii::$app->request->get('multiple'),
            'aspectRatio' => Yii::$app->request->get('aspectRatio'),
        ]);
    }
}