<?php
namespace backend\modules\wechat\controllers;

use Yii;
use common\models\wechat\ReplyDefault;

/**
 * 默认回复控制器
 *
 * Class ReplyDefault
 * @package backend\modules\wechat\controllers
 */
class ReplyDefaultController extends WController
{
    /**
     * 首页
     */
    public function actionIndex()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->message('修改成功', $this->redirect(['index']));
        }

        return $this->render('index',[
            'model' => $model,
        ]);
    }

    /**
     * 返回模型
     *
     * @return array|ReplyDefault|null|yii\db\ActiveRecord
     */
    protected function findModel()
    {
        if (empty(($model = ReplyDefault::find()->one())))
        {
            return new ReplyDefault;
        }

        return $model;
    }
}