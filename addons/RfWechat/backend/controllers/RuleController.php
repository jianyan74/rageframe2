<?php

namespace addons\RfWechat\backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use common\enums\StatusEnum;
use addons\RfWechat\common\models\Rule;
use addons\RfWechat\common\models\RuleKeyword;
use common\components\MerchantCurd;
use addons\RfWechat\backend\forms\RuleForm;


/**
 * Class RuleController
 * @package addons\RfWechat\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class RuleController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Rule
     */
    public $modelClass = Rule::class;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $module = $request->get('module', null);
        $keyword = $request->get('keyword', null);

        $data = Rule::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'module', array_keys(Rule::$moduleExplain)])
            ->andFilterWhere(['module' => $module])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['like', 'name', $keyword]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort desc, created_at desc')
            ->with('ruleKeyword')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'modules' => Rule::$moduleExplain,
            'module' => $module,
            'keyword' => $keyword,
        ]);
    }

    /**
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $defaultRuleKeywords = Yii::$app->wechatServices->ruleKeyword->getType($model->ruleKeyword);
        $postData = Yii::$app->request->post();

        $this->activeFormValidate($model);
        if ($model->load($postData)) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->save()) {
                    throw new \Exception($this->getError($model));
                }

                // 全部关键字
                $ruleKey = $postData['ruleKey'] ?? [];
                $ruleKey[RuleKeyword::TYPE_MATCH] = explode(',', $model->keyword);
                // 更新关键字
                Yii::$app->wechatServices->ruleKeyword->update($model, $ruleKey, $defaultRuleKeywords);
                $transaction->commit();

                return $this->redirect(['index', 'module' => $model->module]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'ruleKeywords' => $defaultRuleKeywords,
            'modules' => Json::encode(Rule::$moduleExplain),
            'apiList' => Yii::$app->wechatServices->rule->getApiList(),
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return mixed
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = RuleForm::find()->where(['id' => $id])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one()))) {
            $model = new RuleForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}