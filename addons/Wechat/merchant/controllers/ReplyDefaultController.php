<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\helpers\ArrayHelper;
use addons\Wechat\common\models\ReplyDefault;

/**
 * 默认回复控制器
 *
 * Class ReplyDefaultController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ReplyDefaultController extends BaseController
{
    /**
     * 首页
     *
     * @return mixed|string
     */
    public function actionIndex()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->message('修改成功', $this->redirect(['index']));
        }

        // 关键字
        $keyword = Yii::$app->wechatService->ruleKeyword->getList();
        $keyword = ArrayHelper::map($keyword, 'content', 'content');
        $keyword = ArrayHelper::merge([' ' => '不触发关键字'], $keyword);

        return $this->render('index', [
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
        if (empty(($model = ReplyDefault::findOne(['merchant_id' => $this->getMerchantId()])))) {
            return new ReplyDefault;
        }

        return $model;
    }
}