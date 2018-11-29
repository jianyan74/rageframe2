<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\data\Pagination;
use common\components\CurdTrait;
use common\models\wechat\FansTags;
use common\models\wechat\MassRecord;
use backend\modules\wechat\models\SendForm;

/**
 * 群发消息控制器
 *
 * Class MassRecordController
 * @package backend\modules\wechat\controllers
 */
class MassRecordController extends WController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\wechat\MassRecord';

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = MassRecord::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'mediaType' => MassRecord::$mediaTypeExplain
        ]);
    }

    /**
     * 编辑/新增
     *
     * @return mixed
     */
    public function actionEdit($media_type)
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        $model->media_type = $media_type;

        if ($model->load($request->post()))
        {
            $model->send_time = strtotime($model->send_time);
            $immediately = $model->send_type == 1 ? true : false;

            try
            {
                if ($model->send($immediately))
                {
                    return $this->redirect(['index']);
                }
            }
            catch (\Exception $e)
            {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'media_type' => $media_type,
            'tags' => FansTags::getList(),
        ]);
    }

    /**
     * 获取粉丝分组 - 群发
     *
     * @return string
     */
    public function actionSend()
    {
        return $this->renderAjax('send-fans',[
            'tags' => FansTags::getList(),
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
        if (empty($id) || empty(($model = SendForm::findOne($id))))
        {
            $model = new SendForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}