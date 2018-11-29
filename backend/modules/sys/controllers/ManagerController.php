<?php
namespace backend\modules\sys\controllers;

use Yii;
use yii\data\Pagination;
use common\components\CurdTrait;
use common\models\sys\Manager;
use common\models\sys\AuthAssignment;
use common\models\sys\AuthItem;
use backend\modules\sys\models\PasswdForm;

/**
 * 后台管理员控制器
 *
 * Class ManagerController
 * @package backend\modules\sys\controllers
 */
class ManagerController extends SController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\sys\Manager';

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $keyword = Yii::$app->request->get('keyword', null);

        $data = Manager::find()
            ->orFilterWhere(['like', 'username', $keyword])
            ->orFilterWhere(['like', 'mobile', $keyword])
            ->orFilterWhere(['like', 'realname', $keyword]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('type desc, id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'keyword' => $keyword,
        ]);
    }

    /**
     * 个人中心
     *
     * @return mixed|string
     */
    public function actionPersonal()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);

        // 提交表单
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->message('修改个人信息成功', $this->redirect(['personal']));
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 修改密码
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionUpPassword()
    {
        $model = new PasswdForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $manager = Yii::$app->user->identity;
            $manager->password_hash = Yii::$app->security->generatePasswordHash($model->passwd_new);;

            // 记录日志
            Yii::$app->debris->log('updateManagerPwd', '修改管理员密码|账号:' . Yii::$app->user->identity->username, false);

            if ($manager->save())
            {
                // 退出登陆
                Yii::$app->user->logout();
                return $this->goHome();
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 角色授权
     *
     * @return array|mixed|string
     */
    public function actionAuthRole()
    {
        $request = Yii::$app->request;
        // 用户id
        $user_id = $request->get('user_id');
        // 角色
        $role = AuthItem::find()->where(['type' => AuthItem::ROLE])->all();
        // 模型
        $model = AuthAssignment::find()->where(['user_id' => $user_id])->one();

        if (!$model)
        {
            $model = new AuthAssignment();
            $model->user_id = $user_id;
        }

        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }
            else
            {
                $AuthAssignment = new AuthAssignment();
                // 返回状态
                if ($AuthAssignment->setAuthRole($model->user_id, $model->item_name))
                {
                    return $this->message('授权成功', $this->redirect(['index']));
                }

                return $this->message('授权失败,角色可能已经被删除！', $this->redirect(['index']), 'error');
            }
        }

        return $this->renderAjax('auth-role', [
            'model' => $model,
            'role' => $role,
            'user_id'=> $user_id,
        ]);
    }
}