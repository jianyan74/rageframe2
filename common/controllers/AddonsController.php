<?php
namespace common\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use common\helpers\ResultDataHelper;
use common\helpers\AddonHelper;
use common\models\wechat\Rule;
use common\models\wechat\RuleKeyword;
use backend\modules\wechat\models\RuleForm;

/**
 * 模块插件渲染
 *
 * Class AddonsController
 * @package common\controllers
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

    public function init()
    {
        parent::init();

        $this->route = Yii::$app->request->get('route', null) ?? Yii::$app->request->post('route');
        $this->addonName = Yii::$app->request->get('addon', null) ?? Yii::$app->request->post('addon');
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        // 关闭csrf
        $action->id == 'execute' && $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 跳转插件详情页面
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidRouteException
     */
    public function actionExecute()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);
        // 解析路由
        AddonHelper::analysisRoute($this->route, AddonHelper::getAppName());
        // 替换
        Yii::$classMap['yii\data\Pagination'] = '@backend/components/Pagination.php';// 分页

        // 实例化解获取数据
        return $this->rendering();
    }

    /**
     * 导航入口
     *
     * @return string
     * @throws NotFoundHttpException
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
     * @throws NotFoundHttpException
     */
    public function actionBlank()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        return $this->render($this->action->id);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRule()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        $request = Yii::$app->request;
        $keyword = $request->get('keyword', null);

        $data = Rule::find()->with('ruleKeyword')
            ->where(['module' => Rule::RULE_MODULE_ADDON])
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
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
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
        $data = Yii::$app->request->get();
        if (!($model = Rule::findOne($id)))
        {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $model->attributes = $data;
        if (!$model->save())
        {
            return ResultDataHelper::json(422, Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::json(200, '修改成功');
    }

    /**
     * 二维码渲染
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionQrCode()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        $qr = Yii::$app->get('qr');
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

        return $qr->setText(Yii::$app->request->get('url', null))
            ->setSize(150)
            ->setMargin(7)
            ->writeString();
    }

    /**
     * 渲染
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidRouteException
     */
    protected function rendering()
    {
        $oldAction = Yii::$app->params['addonInfo']['oldAction'];
        $id = Yii::$app->params['addonInfo']['controller'];
        $controllersPath = Yii::$app->params['addonInfo']['controllersPath'];
        $parts = Yii::createObject($controllersPath, [$id, $this]);

        $params = Yii::$app->request->get();
        /* @var $controller \yii\base\Controller */
        list($controller, $actionID) = [$parts, $oldAction];
        $oldController = Yii::$app->controller;
        Yii::$app->controller = $controller;
        $result = $controller->runAction($actionID, $params);

        if ($oldController !== null)
        {
            Yii::$app->controller = $oldController;
        }

        return $result;
    }
}