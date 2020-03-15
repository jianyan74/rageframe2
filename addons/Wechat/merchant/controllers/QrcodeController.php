<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Response;
use common\traits\MerchantCurd;
use addons\Wechat\common\models\Qrcode;
use common\helpers\ResultHelper;

/**
 * 微信二维码管理
 *
 * Class QrcodeController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class QrcodeController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Qrcode
     */
    public $modelClass = Qrcode::class;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $data = Qrcode::find()->where(['merchant_id' => $this->getMerchantId()]);
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
     * @return mixed|string|Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        /** @var Qrcode $model */
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->isNewRecord && $model = Yii::$app->wechatService->qrcode->syncCreate($model);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 删除全部过期的二维码
     *
     * @return mixed
     */
    public function actionDeleteAll()
    {
        if (Qrcode::deleteAll([
            'and',
            ['model' => Qrcode::MODEL_TEM],
            ['<', 'end_time', time()],
            ['merchant_id' => $this->getMerchantId()]
        ])) {
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
        $url = Yii::$app->wechat->app->qrcode->url($model['ticket']);

        header("Cache-control:private");
        header('content-type:image/jpeg');
        header('content-disposition: attachment;filename="' . $model['name'] . '_' . time() . '.jpg"');
        readfile($url);
    }

    /**
     * 长链接二维码
     *
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionLongUrl()
    {
        if (Yii::$app->request->isAjax) {
            $postUrl = Yii::$app->request->post('shortUrl', '');
            $shortUrl = Yii::$app->wechat->app->url->shorten($postUrl);

            if ($error = Yii::$app->debris->getWechatError($shortUrl, false)) {
                return ResultHelper::json(422, $error);
            }

            return ResultHelper::json(200, '二维码转化成功', [
                'short_url' => $shortUrl['short_url']
            ]);
        }

        return $this->render('long-url');
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