<?php

namespace backend\modules\common\controllers;

use Yii;
use common\helpers\FileHelper;
use backend\controllers\BaseController;

/**
 * Class SystemController
 * @package backend\modules\base\controllers
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
            'mysql_size' => Yii::$app->services->backend->getDefaultDbSize(),
            'attachment_size' => $attachmentSize ?? 0,
            'disable_functions' => $disableFunctions,
        ]);
    }
}