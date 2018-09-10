<?php
namespace api\modules\v1\controllers;

use Yii;
use common\helpers\UploadHelper;
use api\controllers\OnAuthController;

/**
 * 资源上传控制器
 *
 * Class FileController
 * @package api\modules\v1\controllers
 */
class FileController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImages()
    {
        $result = UploadHelper::upload('file', 'images');
        return [
            'urlPath' => $result['relativePath'] . $result['name'],
        ];
    }

    /**
     * 视频上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVideos()
    {
        $result = UploadHelper::upload('file', 'videos');
        return [
            'urlPath' => $result['relativePath'] . $result['name'],
        ];
    }

    /**
     * 语音上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVoices()
    {
        $result = UploadHelper::upload('file', 'voices');
        return [
            'urlPath' => $result['relativePath'] . $result['name'],
        ];
    }

    /**
     * 文件上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFiles()
    {
        $result = UploadHelper::upload('file', 'files');
        return [
            'urlPath' => $result['relativePath'] . $result['name'],
        ];
    }

    /**
     * base64编码的图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBase64Img()
    {
        return UploadHelper::Base64Img(Yii::$app->request->post('image'));
    }

    /**
     * 七牛云存储
     *
     * @return array
     * @throws \Exception
     */
    public function actionQiniu()
    {
        return UploadHelper::qiniu($_FILES['file']);
    }

    /**
     * 阿里云OSS上传
     *
     * @return array
     * @throws \Exception
     */
    public function actionOss()
    {
        return UploadHelper::oss($_FILES['file']);
    }
}