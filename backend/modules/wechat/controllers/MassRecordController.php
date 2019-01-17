<?php
namespace backend\modules\wechat\controllers;

use common\enums\StatusEnum;
use common\models\wechat\Attachment;
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
 * @author jianyan74 <751393839@qq.com>
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
     * 编辑/创建
     *
     * @param $media_type
     * @return mixed|string|\yii\web\Response
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $media_type = Yii::$app->request->get('media_type');
        $model = $this->findModel($id);
        $model->media_type = $media_type;
        $model->send_status == StatusEnum::DISABLED && $model->send_type = 2;

        if ($model->load($request->post()))
        {
            // 获取图文资源文件
            if ($model->media_type == Attachment::TYPE_NEWS)
            {
                $attachment = Attachment::findById($model->attachment_id);
                $model->media_id = $attachment['media_id'];
            }

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
            'submit' => true,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionView()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $media_type = Yii::$app->request->get('media_type');
        $model = $this->findModel($id);
        $model->media_type = $media_type;
        $model->send_status == StatusEnum::DISABLED && $model->send_type = 2;

        return $this->render('edit', [
            'model' => $model,
            'media_type' => $media_type,
            'tags' => FansTags::getList(),
            'submit' => false,
        ]);
    }

    /**
     * 获取粉丝分组 - 群发
     *
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
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