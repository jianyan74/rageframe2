<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Response;
use common\models\wechat\Qrcode;
use common\helpers\ResultDataHelper;

/**
 * 微信二维码管理
 *
 * Class QrcodeController
 * @package backend\modules\wechat\controllers
 */
class QrcodeController extends WController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $data = Qrcode::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    /**
     * 创建
     *
     * @return string|yii\web\Response
     */
    public function actionAdd()
    {
        $model = new Qrcode();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {

            $qrcode = $this->app->qrcode;
            try
            {
                if ($model->model == Qrcode::MODEL_TEM)
                {
                    $scene_id = Qrcode::getSceneId();
                    $result = $qrcode->temporary($scene_id, $model->expire_seconds);
                    $model->scene_id = $scene_id;
                    $model->expire_seconds = $result['expire_seconds']; // 有效秒数
                }
                else
                {
                    $result = $qrcode->forever($model->scene_str);// 或者 $qrcode->forever("foo");
                }

                $model->ticket = $result['ticket'];
                $model->type = 'scene';
                $model->url = $result['url']; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
                $model->save();
            }
            catch (\Exception $e)
            {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error', 0, false);
            }

            return $this->redirect(['index']);
        }

        return $this->renderAjax('add', [
            'model' => $model,
        ]);
    }

    /**
     * ajax编辑
     *
     * @return string|yii\web\Response
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = Qrcode::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 验证表单
     *
     * @return array
     */
    public function actionValidateForm()
    {
        $model = new Qrcode();
        $model->load(Yii::$app->request->post());

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \yii\widgets\ActiveForm::validate($model);
    }

    /**
     * 删除全部过期的二维码
     *
     * @return mixed
     */
    public function actionDeleteAll()
    {
        if (Qrcode::deleteAll(['and', ['model' => Qrcode::MODEL_TEM], ['<', 'end_time', time()]]))
        {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 删除二维码
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if (Qrcode::findOne($id)->delete())
        {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * 下载二维码
     */
    public function actionDown()
    {
        $id = Yii::$app->request->get('id');
        $model = Qrcode::findOne($id);
        $url = $this->app->qrcode->url($model['ticket']);

        header("Cache-control:private");
        header('content-type:image/jpeg');
        header('content-disposition: attachment;filename="' . $model['name'] . '_' . time() . '.jpg"');
        readfile($url);
    }

    /**
     * 长链接二维码
     *
     * @return array|string
     */
    public function actionLongUrl()
    {
        if (Yii::$app->request->isAjax)
        {
            $postUrl = Yii::$app->request->post('shortUrl', '');

            // 长链接转短链接
            $shortUrl = $this->app->url->shorten($postUrl);
            if ($error = Yii::$app->debris->getWechatError($shortUrl, false))
            {
                return ResultDataHelper::json(422, $error);
            }

            return ResultDataHelper::json(200, '二维码转化成功', [
                'short_url' => $shortUrl['short_url']
            ]);
        }

        return $this->render('long-url', [
        ]);
    }

    /**
     * 二维码转换
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionQr()
    {
        $getUrl = Yii::$app->request->get('shortUrl', Yii::$app->request->hostInfo);

        $qr = Yii::$app->get('qr');
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

        return $qr->setText($getUrl)
            ->setSize(150)
            ->setMargin(7)
            ->writeString();
    }
}