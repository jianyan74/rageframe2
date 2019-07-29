<?php

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use common\helpers\Url;
use common\enums\AuthEnum;
use common\helpers\Auth;
use common\models\common\Addons;
use common\helpers\AddonHelper;
use common\models\wechat\Rule;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;
use common\helpers\StringHelper;
use common\models\wechat\RuleKeyword;
use backend\modules\wechat\forms\RuleForm;

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

    public function init()
    {
        parent::init();
        $this->addonName = Yii::$app->request->get('addon', Yii::$app->request->post('addon', ''));
        $this->addonName = StringHelper::strUcwords($this->addonName);

        Yii::$app->params['inAddon'] = true;
    }

    /**
     * @param $action
     * @return bool|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            throw new UnauthorizedHttpException('未登录');
        }

        // 验证是否超级管理员或空权限
        if ($action->id == 'blank') {
            throw new NotFoundHttpException('找不到可用菜单，请检查自己的配置或者联系管理员');
        }

        // 权限校验
        $route = '';
        in_array($action->id, ['cover']) && $route = Addons::AUTH_COVER;
        in_array($action->id, ['rule', 'rule-edit', 'rule-delete', 'ajax-update']) && $route = Addons::AUTH_RULE;
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
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        $covers = [];
        $baseCover = Yii::$app->params['addonBinding']['cover'];

        foreach ($baseCover as $value) {
            $key = AuthEnum::$typeExplain[$value['type']] . '入口';
            $value['url'] = '';

            switch ($value['type']) {
                case AuthEnum::TYPE_API :
                    $value['url'] = Url::toApi(ArrayHelper::merge([$value['route']], $value['params']));
                    break;
                case AuthEnum::TYPE_FRONTEND :
                    $value['url'] = Url::toFront(ArrayHelper::merge([$value['route']], $value['params']));
                    break;
                case AuthEnum::TYPE_WECHAT :
                    $value['url'] = Url::toWechat(ArrayHelper::merge([$value['route']], $value['params']));
                    break;
                case AuthEnum::TYPE_OAUTH2 :
                    $value['url'] = Url::toOAuth2(ArrayHelper::merge([$value['route']], $value['params']));
                    break;
                case AuthEnum::TYPE_STORAGE:
                    $value['url'] = Url::toStorage(ArrayHelper::merge([$value['route']], $value['params']));
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
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRule()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

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
     * @throws \yii\base\ExitException
     */
    public function actionRuleEdit()
    {
        // 初始化模块
        AddonHelper::initAddon($this->addonName, $this->route);

        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->module = Rule::RULE_MODULE_ADDON;
        $model->data = $this->addonName;
        $defaultRuleKeywords = Yii::$app->services->wechatRuleKeyword->getType($model->ruleKeyword);

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
                Yii::$app->services->wechatRuleKeyword->update($model, $ruleKey, $defaultRuleKeywords);
                $transaction->commit();

                return $this->redirect(['/addons/rule', 'addon' => $this->addonName]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', $e->getMessage());
                return $this->redirect(['/addons/rule', 'addon' => $this->addonName]);
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
            return $this->redirect(['/addons/rule', 'addon' => $this->addonName]);
        }

        Yii::$app->getSession()->setFlash('error', '删除失败');
        return $this->redirect(['/addons/rule', 'addon' => $this->addonName]);
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
            return ResultDataHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save()) {
            return ResultDataHelper::json(422, Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::json(200, '修改成功');
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