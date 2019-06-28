<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use common\enums\StatusEnum;
use common\helpers\ResultDataHelper;
use common\models\wechat\Attachment;
use backend\modules\wechat\forms\PreviewForm;
use backend\modules\wechat\forms\SendForm;
use backend\controllers\BaseController;

/**
 * 资源
 *
 * Class AttachmentController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $keywords = Yii::$app->request->get('keywords', '');
        $type = Yii::$app->request->get('type', Attachment::TYPE_NEWS);

        $data = Attachment::find()
            ->where(['media_type' => $type, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['like', 'file_name', $keywords]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => 15]);
        $type == Attachment::TYPE_NEWS && $data = $data->with('news');
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($type, [
            'models' => $models,
            'pages' => $pages,
            'mediaType' => $type,
            'keywords' => $keywords,
            'allMediaType' => Attachment::$typeExplain,
        ]);
    }

    /**
     * 图文编辑
     *
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionNewsEdit()
    {
        $request = Yii::$app->request;
        $attach_id = $request->get('attach_id', '');
        $attachment = $this->findModel($attach_id);
        $attachment->link_type = $request->get('link_type', Attachment::LINK_TYPE_WECHAT);
        $attachment->media_type = Attachment::TYPE_NEWS;

        if ($request->isAjax) {
            // 事务
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $isNewRecord = $attachment->isNewRecord;
                $attachment->save();
                $list = Json::decode($request->post('list'));
                Yii::$app->services->wechatAttachment->editNews($attachment, $list, $isNewRecord);
                $transaction->commit();

                return ResultDataHelper::json(200, '修改成功');
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ResultDataHelper::json(422, $e->getMessage());
            }
        }

        return $this->render('news-edit',[
            'attachment' => $attachment,
            'list' => Yii::$app->services->wechatAttachmentNews->formattingList($attach_id),
        ]);
    }

    /**
     * 创建
     *
     * @param $type
     * @return mixed|string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionCreate($type)
    {
        $model = new Attachment;
        $model->media_type = $type;
        if ($model->load(Yii::$app->request->post()) && $model->local_url) {
            try {
                $res = Yii::$app->services->wechatAttachment->saveCreate($model);
                return $this->message("创建成功", $this->redirect(['index', 'type' => $type]));
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index', 'type' => $type]), 'error');
            }
        }

        return $this->renderAjax($type . '-create',[
            'model' => $model
        ]);
    }

    /**
     * 删除永久素材
     *
     * @param $attach_id
     * @param $mediaType
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionDelete($attach_id, $mediaType)
    {
        // 删除数据库
        $model = $this->findModel($attach_id);
        if ($model->delete()) {
            // 删除微信服务器数据
            $result = Yii::$app->wechat->app->material->delete($model['media_id']);
            if ($error = Yii::$app->debris->getWechatError($result, false)) {
                return $this->message($error, $this->redirect(['index', 'type' => $mediaType]), 'error');
            }

            return $this->message("删除成功", $this->redirect(['index', 'type' => $mediaType]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'type' => $mediaType]), 'error');
    }

    /**
     * 手机预览
     *
     * @param $attach_id
     * @param $mediaType
     * @return mixed|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionPreview($attach_id, $mediaType)
    {
        $model = new PreviewForm();
        if ($model->load(Yii::$app->request->post())) {
            try {
                Yii::$app->services->wechatAttachment->preview($attach_id, $model->type, $model->content);
                return $this->message("发送成功", $this->redirect(['index', 'type' => $mediaType]));
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index', 'type' => $mediaType]), 'error');
            }
        }

        return $this->renderAjax('preview',[
            'model' => $model,
        ]);
    }

    /**
     * 消息群发
     *
     * @param $attach_id
     * @param $mediaType
     * @return mixed|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionSend($data, $mediaType)
    {
        $model = new SendForm();
        $model->$mediaType = $data;
        $model->module = $mediaType;
        $model->send_time = time();
        if ($model->load(Yii::$app->request->post())) {
            try {
                if (!$model->save()) {
                    throw new UnprocessableEntityHttpException($this->getError($model));
                }

                return $this->message('发送成功', $this->redirect(['attachment/index', 'type' => $mediaType]));
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['attachment/index', 'type' => $mediaType]), 'error');
            }
        }

        return $this->renderAjax('send',[
            'model' => $model,
            'tags' => Yii::$app->services->wechatFansTags->getList(),
        ]);
    }

    /**
     * 同步
     *
     * @param $type
     * @param int $offset
     * @param int $count
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionSync($type, $offset = 0, $count = 20)
    {
        // 查找素材
        try {
            $res = Yii::$app->services->wechatAttachment->sync($type, $offset, $count);
            if (is_array($res)) {
                return ResultDataHelper::json(200, '同步成功', $res);
            }

            return ResultDataHelper::json(201, '同步完成');
        } catch (\Exception $e) {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return array|Attachment|null|\yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Attachment::find()->where(['id' => $id])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one()))) {
            $model = new Attachment;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}