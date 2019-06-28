<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use common\helpers\StringHelper;
use addons\RfExample\common\models\CutImageForm;

/**
 * Class VideoController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class VideoController extends BaseController
{
    /**
     * @return string
     */
    public function actionCutImage()
    {
        $model = new CutImageForm();
        if ($model->load(Yii::$app->request->post())) {
            $filePath = StringHelper::getLocalFilePath($model->video);
            $img = Yii::getAlias("@attachment/") . "test1.jpg";

            // ffmpeg获取视频帧 -i 后面是输出 -y 是质量 -f 是输出格式  -t 时间
            // shell_exec("ffmpeg -i {$filePath} -y -f image2 -t 0.05 -s 352*240 {$img}");

            shell_exec("ffmpeg -ss 00:00:01  -i {$filePath} -f mjpeg -r 1 -vframes 1 -an {$img}");
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}