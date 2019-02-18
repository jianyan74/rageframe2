<?php
namespace backend\modules\member\controllers;

use Yii;
use common\models\common\SearchModel;
use common\components\CurdTrait;
use common\models\member\MemberInfo;
use common\enums\StatusEnum;

/**
 * 会员管理
 *
 * Class MemberController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends MController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\member\MemberInfo';

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => MemberInfo::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return array|mixed|string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->scenario = 'backendCreate';
        $modelInfo = clone $model;
        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            // 验证密码
            if ($modelInfo['password_hash'] != $model->password_hash)
            {
                // 记录行为日志
                Yii::$app->services->sys->log('updateMemberPwd', '创建/修改用户密码|账号:' . $model->username, false);

                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
            }

            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}