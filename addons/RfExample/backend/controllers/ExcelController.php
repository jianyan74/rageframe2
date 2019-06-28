<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use common\helpers\ExcelHelper;
use common\controllers\AddonsController;

/**
 * Class ExcelController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ExcelController extends AddonsController
{
    /**
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            try {
                $file = $_FILES['excelFile'];
                $data = ExcelHelper::import($file['tmp_name'], 2);
                // \common\helpers\RfImportHelper::auth($data);
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }

            p($data);die();
        }

        return $this->render('index', [

        ]);
    }
}