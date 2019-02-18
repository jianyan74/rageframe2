<?php
namespace backend\modules\sys\controllers;

use yii;
use common\helpers\ArrayHelper;
use common\models\sys\AddonsAuthItemChild;
use common\helpers\ResultDataHelper;
use common\models\sys\AuthItem;
use common\models\sys\AuthItemChild;

/**
 * RBAC角色控制器
 *
 * Class AuthRoleController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
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
        /* @var $models \common\models\sys\AuthItem */
        list($models, $parent_key, $treeStat) = Yii::$app->services->sys->auth->getChildRoles();

        return $this->render('index', [
            'models' => ArrayHelper::itemsMerge($models, $parent_key, 'key', 'parent_key'),
            'treeStat' => $treeStat
        ]);
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

        if ($request->isAjax)
        {
            $model->attributes = $request->post();
            $model->description = Yii::$app->user->identity->username . '添加了角色';
            if (!$model->save())
            {
                return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
            }

            $userTreeIds = $request->post('userTreeIds', []);
            $plugTreeIds = $request->post('plugTreeIds', []);

            // 增加的用户权限
            $addAuths = AuthItem::find()
                ->where(['type' => AuthItem::AUTH])
                ->andWhere(['in', 'key', $userTreeIds])
                ->select('name')
                ->asArray()
                ->all();

            // 校验是否在自己的权限下
            $useAuth = Yii::$app->services->sys->auth->getUserAuth();
            $allAuth = array_merge(array_intersect(array_column($useAuth, 'name'), array_column($addAuths, 'name')));

            if (!(AuthItemChild::accredit($model->name, $allAuth)))
            {
                return ResultDataHelper::json(404, '权限提交失败');
            }

            // 增加用户插件权限
            $addAddonAuths = [];
            foreach ($plugTreeIds as $plugTreeId)
            {
                $arrTreeId = explode(':', $plugTreeId);
                $type = AddonsAuthItemChild::TYPE_COVER;
                if (isset($arrTreeId[1]) && !isset(AddonsAuthItemChild::$authExplain[$arrTreeId[1]]))
                {
                    $type = AddonsAuthItemChild::TYPE_MENU;
                }

                $addAddonAuths[] = [
                    'child' => $plugTreeId,
                    'addons_name' => $arrTreeId[0],
                    'type' => $type
                ];
            }

            if (!(AddonsAuthItemChild::accredit($model->name, $addAddonAuths)))
            {
                return ResultDataHelper::json(404, '插件权限提交失败');
            }

            /**
             * 记录行为日志
             *
             * 由于数据与预期的不符手动写入Post数据
             */
            Yii::$app->request->setBodyParams(ArrayHelper::merge($request->post(), ['userTrees' => $allAuth]));
            Yii::$app->services->sys->log('authEdit', '创建/编辑角色 or 权限');

            return ResultDataHelper::json(200, '提交成功');
        }

        $sysAuth = Yii::$app->services->sys->auth;
        // 当前用户权限
        list($userTreeData, $userTreeCheckIds) = $sysAuth->getAuthJsTreeData($name);

        // 插件权限管理
        list($plugTreeData, $plugTreeCheckIds) = $sysAuth->getAddonsAuthJsTreeData($name);

        // jq冲突禁用
        $this->forbiddenJq();

        return $this->render('edit', [
            'model' => $model,
            'userTreeData' => $userTreeData,
            'userTreeCheckIds' => $userTreeCheckIds,
            'plugTreeData' => $plugTreeData,
            'plugTreeCheckIds' => $plugTreeCheckIds,
            'name' => $name,
            'parentTitle' => $request->get('parent_title', '无'),
            'parentKey' => $request->get('parent_key', 0),
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
        // 记录行为日志
        Yii::$app->services->sys->log('authDel', '删除角色');

        if ($this->findModel($name)->delete())
        {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * ajax更新排序/状态
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = AuthItem::findOne(['key' => $id])))
        {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $data = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        $model->attributes = $data;
        if (!$model->save())
        {
            return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::json(200, '修改成功');
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
            $model = $model->loadDefaultValues();
            $model->type = AuthItem::ROLE;

            return $model;
        }

        return $model;
    }
}