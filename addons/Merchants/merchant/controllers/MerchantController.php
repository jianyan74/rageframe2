<?php

namespace addons\Merchants\merchant\controllers;

use Yii;
use common\traits\Curd;
use addons\Merchants\merchant\forms\MerchantForm;

/**
 * Class MerchantController
 * @package addons\Merchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantController extends BaseController
{
    use Curd;

    /**
     * @var MerchantForm
     */
    public $modelClass = MerchantForm::class;

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->user->identity->merchant_id;
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->message('修改成功', $this->redirect(['edit']));
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'cates' => Yii::$app->services->merchantCate->getMapList(),
        ]);
    }
}