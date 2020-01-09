<?php

namespace addons\RfArticle\merchant\controllers;

use Yii;
use common\traits\MerchantCurd;
use addons\RfArticle\common\models\Adv;

/**
 * 幻灯片
 *
 * Class AdvController
 * @package addons\RfArticle\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AdvController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Adv
     */
    public $modelClass = Adv::class;

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'locals' => Adv::$localExplain,
        ]);
    }
}