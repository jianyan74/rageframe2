<?php
namespace api\modules\v1\controllers;

use Yii;
use common\helpers\UploadHelper;
use api\controllers\OnAuthController;
use yii\web\NotFoundHttpException;

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
        // 载入配置信息
        UploadHelper::load(Yii::$app->request->post(), 'images');
        // 上传
        $result = UploadHelper::file();

        return $result;
    }

    /**
     * 视频上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVideos()
    {
        // 载入配置信息
        UploadHelper::load(Yii::$app->request->post(), 'videos');
        // 上传
        $result = UploadHelper::file();

        return $result;
    }

    /**
     * 语音上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVoices()
    {
        // 载入配置信息
        UploadHelper::load(Yii::$app->request->post(), 'voices');
        // 上传
        $result = UploadHelper::file();

        return $result;
    }

    /**
     * 文件上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFiles()
    {
        // 载入配置信息
        UploadHelper::load(Yii::$app->request->post(), 'files');
        // 上传
        $result = UploadHelper::file();

        return $result;
    }

    /**
     * base64编码的图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBase64Img()
    {
        return UploadHelper::Base64Img(Yii::$app->request->post('image'), Yii::$app->request->post('extend', 'jpg'));
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

    /**
     * 合并
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionMerge()
    {
        $guid = Yii::$app->request->post('guid');
        $mergeInfo = Yii::$app->cache->get(UploadHelper::$prefixForMergeCache . $guid);

        if (!$mergeInfo)
        {
            throw new NotFoundHttpException('找不到文件信息, 合并文件失败');
        }

        UploadHelper::mergeFile($mergeInfo['ultimatelyFilePath'], $mergeInfo['tmpAbsolutePath'], 1, $mergeInfo['extension']);

        Yii::$app->cache->delete('upload-file-guid:' . $guid);

        return [
            'urlPath' => $mergeInfo['relativePath']
        ];
    }
}