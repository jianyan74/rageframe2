<?php
namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\wechat\Rule;
use common\models\wechat\RuleKeyword;
use common\helpers\ResultDataHelper;
use common\helpers\AddonHelper;
use common\models\sys\AddonsAuthItemChild;
use common\helpers\AddonAuthHelper;
use common\helpers\DebrisHelper;
use backend\modules\wechat\models\RuleForm;

/**
 * Class AddonsController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsController extends \common\controllers\AddonsController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws UnauthorizedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest)
        {
            throw new UnauthorizedHttpException('对不起，请先登录');
        }

        // 判断是否手机
        Yii::$app->params['isMobile'] = DebrisHelper::isMobile();

        // 验证是否登录且验证是否超级管理员
        if (!Yii::$app->user->isGuest && Yii::$app->services->sys->isAuperAdmin())
        {
            return true;
        }

        if ($action->id == 'blank')
        {
            return true;
        }

        // 当前菜单路由
        $route = $this->route;
        in_array($action->id, ['cover']) && $route = AddonsAuthItemChild::AUTH_COVER;
        in_array($action->id, ['rule', 'rule-edit', 'rule-delete', 'ajax-update']) && $route = AddonsAuthItemChild::AUTH_RULE;
        if (AddonAuthHelper::verify($route) === false)
        {
            throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }

        return parent::beforeAction($action);
    }

    /**
     * 导航入口
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCover()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        return $this->render($this->action->id);
    }

    /**
     * 空白页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBlank()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        return $this->render($this->action->id);
    }

    /**
     * 规则
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRule()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        $request = Yii::$app->request;
        $keyword = $request->get('keyword', null);

        $data = Rule::find()
            ->where(['module' => Rule::RULE_MODULE_ADDON])
            ->with('ruleKeyword')
            ->joinWith('addon as b')
            ->andWhere(['b.addon' => Yii::$app->params['addon']['name']])
            ->andFilterWhere(['like', 'name', $keyword]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort asc,created_at desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'keyword' => $keyword,
        ]);
    }

    /**
     * 规则编辑
     *
     * @return string|Response
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRuleEdit()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        $request = Yii::$app->request;
        $id = $request->get('id');

        if (empty($id) || empty(($model = RuleForm::findOne($id))))
        {
            $model = new RuleForm();
            $model = $model->loadDefaultValues();
        }

        $model->module = 'addon';// 回复规则

        $defaultRuleKeywords = RuleKeyword::getRuleKeywordsType($model->ruleKeyword);
        $moduleModel = Rule::getModuleModel($id, $model->module);// 基础
        $moduleModel->addon = Yii::$app->params['addon']['name'];

        $postData = $request->post();
        if ($model->load($postData))
        {
            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                if (!$model->save())
                {
                    throw new \Exception(Yii::$app->debris->analyErr($model->getFirstErrors()));
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
                    return $this->redirect(['rule', 'addon' => Yii::$app->params['addon']['name']]);
                }

                throw new \Exception('插入失败');
            }
            catch (\Exception $e)
            {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', $e->getMessage());
                return $this->redirect(['rule', 'addon' => Yii::$app->params['addon']['name']]);
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'moduleModel' => $moduleModel,
            'ruleKeywords' => $defaultRuleKeywords,
        ]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionRuleDelete($id)
    {
        if (empty($id) || empty(($model = RuleForm::findOne($id))))
        {
            $model = new RuleForm();
        }

        if ($model->delete())
        {
            return $this->redirect(['rule', 'addon' => Yii::$app->request->get('addon')]);
        }

        Yii::$app->getSession()->setFlash('error', '删除失败');
        return $this->redirect(['rule', 'addon' => Yii::$app->request->get('addon')]);
    }

    /**
     * 更新排序/状态字段
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = Rule::findOne($id)))
        {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save())
        {
            return ResultDataHelper::json(422, Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::json(200, '修改成功');
    }
}