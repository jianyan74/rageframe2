<?php

namespace addons\RfDevTool\backend\controllers;

use Yii;
use common\helpers\ExcelHelper;
use addons\RfDevTool\common\helpers\ImportHelper;

/**
 * Class AuthController
 * @package addons\RfDevTool\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            try {
                $file = $_FILES['excelFile'];
                $data = ExcelHelper::import($file['tmp_name'], 2);
                ImportHelper::auth($data);
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }

            return $this->message('导入成功', $this->redirect(['index']));
        }

        return $this->render('index', [

        ]);
    }

    /**
     * 下载
     */
    public function actionDownload()
    {
        $path = Yii::getAlias('@addons') . '/RfDevTool/common/file/auth-data.xls';
        Yii::$app->response->sendFile($path, '权限数据_' . time() . '.xls');
    }
}