<?php

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\helpers\Url;
use common\enums\AppEnum;
use common\helpers\Auth;
use common\helpers\AddonHelper;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\behaviors\ActionLogBehavior;
use addons\Wechat\common\models\Rule;
use addons\Wechat\common\models\RuleKeyword;
use addons\Wechat\merchant\forms\RuleForm;

/**
 * Class AddonsController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsController extends Controller
{
    /**
     * @var string
     */
    public $layout = "@backend/views/layouts/addon";

    /**
     * 当前路由
     *
     * @var
     */
    public $route;

    /**
     * 模块名称
     *
     * @var
     */
    public $addonName;

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
            'actionLog' => [
                'class' => ActionLogBehavior::class
            ]
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->addonName = Yii::$app->params['addon']['name'];
        $this->addonName = StringHelper::strUcwords($this->addonName);

        Yii::$app->params['inAddon'] = true;
    }

    /**
     * @param $action
     * @return bool
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        // 验证是否超级管理员或空权限
        if ($action->id == 'blank') {
            throw new NotFoundHttpException('找不到可用菜单，请检查自己的配置或者联系管理员');
        }

        if ($action->id != 'cover') {
            // 动态注入服务
            AddonHelper::service('Wechat');
        }

        // 权限校验
        $route = '/' . Yii::$app->params['addonName'] . '/addons/' . $action->id;
        if (false === Auth::verify($route)) {
            throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }

        return parent::beforeAction($action);
    }

    /**
     * 导航入口
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCover()
    {
        $covers = [];
        $baseCover = Yii::$app->params['addonBinding']['cover'];

        foreach ($baseCover as $value) {
            $key = AppEnum::getValue($value['app_id']) . '入口';
            $value['url'] = '';

            !is_array($value['params']) && $value['params'] = [];
            $route = ArrayHelper::merge([$value['route']], $value['params']);
            switch ($value['app_id']) {
                case AppEnum::API :
                    $value['url'] = Url::toApi($route);
                    break;
                case AppEnum::FRONTEND :
                    $value['url'] = Url::toFront($route);
                    break;
                case AppEnum::HTML5 :
                    $value['url'] = Url::toHtml5($route);
                    break;
                case AppEnum::OAUTH2 :
                    $value['url'] = Url::toOAuth2($route);
                    break;
                case AppEnum::STORAGE:
                    $value['url'] = Url::toStorage($route);
                    break;
            }

            $covers[$key][] = $value;
        }

        return $this->render('@backend/views/addons/cover', [
            'covers' => $covers
        ]);
    }

    /**
     * 规则
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function actionRule()
    {
        $keyword = Yii::$app->request->get('keyword', null);
        $data = Rule::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['module' => Rule::RULE_MODULE_ADDON])
            ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andFilterWhere(['like', 'name', $keyword]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort asc,created_at desc')
            ->with('ruleKeyword')
            ->limit($pages->limit)
            ->all();

        return $this->render('@backend/views/addons/rule', [
            'models' => $models,
            'addonName' => $this->addonName,
            'pages' => $pages,
            'keyword' => $keyword,
        ]);
    }

    /**
     * 规则编辑
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\base\ExitException
     */
    public function actionRuleEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->module = Rule::RULE_MODULE_ADDON;
        $model->data = $this->addonName;
        $defaultRuleKeywords = Yii::$app->wechatService->ruleKeyword->getType($model->ruleKeyword);

        // ajax校验
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                Yii::$app->response->data = \yii\widgets\ActiveForm::validate($model);
                Yii::$app->end();
            }
        }

        $postData = Yii::$app->request->post();
        if ($model->load($postData)) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->save()) {
                    throw new \Exception(Yii::$app->debris->analyErr($model->getFirstErrors()));
                }

                // 全部关键字
                $ruleKey = $postData['ruleKey'] ?? [];
                $ruleKey[RuleKeyword::TYPE_MATCH] = explode(',', $model->keyword);
                // 更新关键字
                Yii::$app->wechatService->ruleKeyword->update($model, $ruleKey, $defaultRuleKeywords);
                $transaction->commit();

                return $this->redirect(['rule', 'addon' => $this->addonName]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', $e->getMessage());
                return $this->redirect(['rule', 'addon' => $this->addonName]);
            }
        }

        return $this->render('@backend/views/addons/rule-edit', [
            'model' => $model,
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
        $model = $this->findModel($id);
        if ($model->delete()) {
            return $this->redirect(['rule', 'addon' => $this->addonName]);
        }

        Yii::$app->getSession()->setFlash('error', '删除失败');
        return $this->redirect(['rule', 'addon' => $this->addonName]);
    }

    /**
     * 更新排序/状态字段
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = Rule::find()->where(['id' => $id])->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])->one())) {
            return ResultHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save()) {
            return ResultHelper::json(422, Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        return ResultHelper::json(200, '修改成功');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return mixed
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = RuleForm::find()->where(['id' => $id])->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])->one()))) {
            $model = new RuleForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}