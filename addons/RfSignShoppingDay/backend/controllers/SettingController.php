<?php
namespace addons\RfSignShoppingDay\backend\controllers;

use Yii;
use common\helpers\ArrayHelper;
use common\controllers\AddonsBaseController;
use backend\interfaces\AddonsSettingInterface;
use addons\RfSignShoppingDay\common\models\SettingForm;

/**
 * 参数设置
 *
 * Class SettingController
 * @package addons\RfSignShoppingDay\backend\controllers
 */
class SettingController extends AddonsBaseController implements AddonsSettingInterface
{
    /**
     * @return mixed|string
     */
    public function actionDisplay()
    {
        $request = Yii::$app->request;
        $model = new SettingForm();
        $model->attributes = $this->getConfig();

        if ($model->load($request->post()) && $model->validate())
        {
            $this->setConfig(ArrayHelper::toArray($model));
            return $this->message('修改成功', $this->redirect(['display']));
        }

        return $this->render('display',[
            'model' => $model,
        ]);
    }

    /**
     * 钩子
     *
     * @param array $param
     * @return mixed|string
     */
    public function actionHook($param = [])
    {
        return $this->render('hook', [
            'param' => $param
        ]);
    }
}