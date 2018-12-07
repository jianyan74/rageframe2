<?php
namespace backend\modules\wechat\controllers;

use Yii;
use common\helpers\ArrayHelper;
use common\models\wechat\RuleKeyword;
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

        // 关键字
        $keyword = RuleKeyword::getList();
        $keyword = ArrayHelper::map($keyword, 'content', 'content');
        $keyword = ArrayHelper::merge([' ' => '不触发关键字'], $keyword);

        return $this->render('index',[
            'model' => $model,
            'keyword' => $keyword
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