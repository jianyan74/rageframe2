<?php
namespace backend\modules\member\controllers;

use Yii;
use common\components\CurdTrait;
use common\models\member\MemberInfo;

/**
 * 会员信息控制器
 * 
 * Class MemberController
 * @package backend\modules\member\controllers
 */
class MemberController extends MController
{
    use CurdTrait;

    public $modelClass = 'common\models\member\MemberInfo';

    /**
     * 编辑/新增
     *
     * @return array|mixed|string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionAjaxEdit()
    {
        $request  = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->scenario = 'backendCreate';
        $modelInfo = clone $model;
        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            // 验证密码
            if ($modelInfo['password_hash'] != $model->password_hash)
            {
                // 记录日志
                Yii::$app->debris->log('updateMemberPwd', '修改用户密码|账号:' . $model->username, false);

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