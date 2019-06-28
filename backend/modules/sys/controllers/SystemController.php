<?php
namespace backend\modules\sys\controllers;

use Yii;
use common\helpers\ResultDataHelper;
use common\helpers\FileHelper;
use backend\controllers\BaseController;

/**
 * Class SystemController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SystemController extends BaseController
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionInfo()
    {
        // 禁用函数
        $disableFunctions = ini_get('disable_functions');
        $disableFunctions = !empty($disableFunctions) ? explode(',', $disableFunctions) : '未禁用';
        // 附件大小
        $attachmentSize = FileHelper::getDirSize(Yii::getAlias('@attachment'));

        return $this->render('info', [
            'mysql_size' => Yii::$app->services->sys->getDefaultDbSize(),
            'attachment_size' => $attachmentSize ?? 0,
            'disable_functions' => $disableFunctions,
        ]);
    }

    /**
     * 服务器探针
     *
     * @return array|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionProbe()
    {
        $info = Yii::$app->services->sys->getProbeInfo();
        if (Yii::$app->request->isAjax) {
            return ResultDataHelper::json(200, '获取成功', $info);
        }

        return $this->render('probe', [
            'info' => $info,
        ]);
    }
}