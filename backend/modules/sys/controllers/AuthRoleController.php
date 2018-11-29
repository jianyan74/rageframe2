<?php
namespace backend\modules\sys\controllers;

use yii;
use yii\data\Pagination;
use common\helpers\ResultDataHelper;
use common\models\sys\AuthItem;
use common\models\sys\AuthItemChild;
use common\models\sys\AuthAssignment;
use common\helpers\ArrayHelper;

/**
 * RBAC角色控制器
 *
 * Class AuthRoleController
 * @package backend\modules\sys\controllers
 */
class AuthRoleController extends SController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $data = AuthItem::find()->where(['type' => AuthItem::ROLE]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * 删除
     *
     * @param $name
     * @return mixed
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($name)
    {
        if ($this->findModel($name)->delete())
        {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 角色授权
     *
     * @return array|string
     * @throws yii\base\InvalidConfigException
     * @throws yii\db\Exception
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $name = $request->get('name');
        $model = $this->findModel($name);

        // 获取当前用户权限
        list($formAuth, $checkId) = $this->getUserAuth($name);

        if ($request->isAjax)
        {
            $name = $request->post('name');
            $model = $this->findModel($request->post('originalName', ''));
            $model->type = AuthItem::ROLE;
            $model->name = $name;
            $model->description = Yii::$app->user->identity->username . "|添加了|" . $model->name . "|角色";
            if (!$model->save())
            {
                return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
            }

            $ids = $request->post('ids', []);
            if (!empty($ids))
            {
                $auths = AuthItem::find()
                    ->where(['type' => AuthItem::AUTH])
                    ->andWhere(['in', 'key', $ids])
                    ->select('name')
                    ->asArray()
                    ->all();

                if ((new AuthItemChild())->accredit($name, array_column($auths, 'name')))
                {
                    return ResultDataHelper::json(200, '提交成功');
                }

                return ResultDataHelper::json(404, '提交失败');
            }

            return ResultDataHelper::json(200, '提交成功');
        }

        // jq冲突禁用
        $this->forbiddenJq();

        return $this->render('edit', [
            'model' => $model,
            'formAuth' => $formAuth,
            'checkId' => $checkId,
            'name' => $name
        ]);
    }

    /**
     * 由于jstree会和系统的js引入冲突，先设置禁用掉
     *
     * @throws yii\base\InvalidConfigException
     */
    private function forbiddenJq()
    {
        Yii::$app->set('assetManager', [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [

                    ]
                ],
            ],
        ]);

    }

    /**
     * 获取当前用户权限
     *
     * @param $name
     * @return array
     */
    private function getUserAuth($name)
    {
        $userAuth = [];
        // 验证是否总管理员, 并获取自己的权限列表
        if (Yii::$app->params['adminAccount'] != Yii::$app->user->id)
        {
            $itemNames = AuthAssignment::finldByUserId(Yii::$app->user->id);
            if (!empty($itemNames['authItemChild']))
            {
                foreach ($itemNames['authItemChild'] as $child)
                {
                    $userAuth[] = $child['child'];
                }
            }
        }

        $auths = AuthItem::find()
            ->where(['type' => AuthItem::AUTH])
            ->andFilterWhere(['in', 'name', $userAuth])
            ->with(['authItemChildren0' => function($query) use($name) {
                $query->andWhere(['parent' => $name]);
            }])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $formAuth = []; // 全部权限
        $checkId = []; // 被授权成功的额权限
        $tmpChildIds = [];
        foreach ($auths as $auth)
        {
            $tmp = [];
            $tmp['id'] = $auth['key'];
            $tmp['parent'] = !empty($auth['parent_key']) ? $auth['parent_key'] : '#';
            $tmp['text'] = $auth['description'];
            // $tmp['icon'] = 'none';

            if (!empty($auth['authItemChildren0']))
            {
                $checkId[] = $auth['key'];
                $tmpChildIds[$auth['key']] = ArrayHelper::getChildsId($auths, $auth['key'], 'key', 'parent_key');
            }

            $formAuth[] = $tmp;
            unset($tmp);
        }

        // 做一次筛选，不然jstree会吧顶级分类下所有的子分类都选择了
        foreach ($tmpChildIds as $key => $tmpChildId)
        {
            if (!empty($tmpChildId) && count(array_intersect($checkId, $tmpChildId)) != count($tmpChildId))
            {
                $checkId = array_merge(array_diff($checkId, [$key]));
            }
        }

        return [$formAuth, $checkId];
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return mixed
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = AuthItem::findOne($id))))
        {
            $model = new AuthItem();
            return $model->loadDefaultValues();
        }

        return $model;
    }

}