<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\models\wechat\Rule;
use common\models\wechat\RuleKeyword;
use common\components\CurdTrait;
use backend\modules\wechat\models\RuleForm;

/**
 * Class RuleController
 * @package backend\modules\wechat\controllers
 */
class RuleController extends WController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\wechat\Rule';

    /**
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $module = $request->get('module', null);
        $keyword = $request->get('keyword', null);

        $data = Rule::find()->where(['>=', 'status', StatusEnum::DISABLED])->with('ruleKeyword')
            ->andWhere(['in', 'module', array_keys(Rule::$moduleExplain)])
            ->andFilterWhere(['module' => $module])
            ->andFilterWhere(['like', 'name', $keyword]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort asc,created_at desc')
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
     * 编辑更新
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');

        $model = $this->findModel($id);
        $model->module = $request->get('module');// 回复规则
        $defaultRuleKeywords = RuleKeyword::getRuleKeywordsType($model->ruleKeyword);
        $moduleModel = Rule::getModuleModel($id, $model->module);// 基础

        $postData = Yii::$app->request->post();
        if ($model->load($postData) && $moduleModel->load($postData))
        {
            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                if (!$model->save())
                {
                   throw new \Exception($this->analyErr($model->getFirstErrors()));
                }

                 // 获取规则ID
                $moduleModel->rule_id = $model->id;
                // 全部关键字
                $ruleKey = isset($postData['ruleKey']) ? $postData['ruleKey'] : [];
                $ruleKey[RuleKeyword::TYPE_MATCH] = explode(',', $model->keyword);

                // 更新关键字
                RuleKeyword::updateKeywords($model, $ruleKey, $defaultRuleKeywords);

                // 插入模块数据
                if ($moduleModel->save())
                {
                    $transaction->commit();
                    return $this->redirect(['index','module' => $model->module]);
                }

                throw new \Exception('插入失败');
            }
            catch (\Exception $e)
            {
                $transaction->rollBack();
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'moduleModel' => $moduleModel,
            'title' => Rule::$moduleExplain[$model->module],
            'ruleKeywords' => $defaultRuleKeywords,
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
        if (empty($id) || empty(($model = RuleForm::findOne($id))))
        {
            $model = new RuleForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}