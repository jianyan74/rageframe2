<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\helpers\UploadHelper;
use common\helpers\ResultDataHelper;
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
     * @return array|mixed|string
     * @throws NotFoundHttpException
     * @throws \OSS\Core\OssException
     */
    public function actionImages()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), 'images');
        $upload->uploadFileName = 'file';
        $upload->verify();
        // 上传
        $url = $upload->save();

        $result = is_array($url) ? $url : ['url' => $url];

        return $result;
    }

    /**
     * 视频上传
     *
     * @return array|mixed|string
     * @throws NotFoundHttpException
     * @throws \OSS\Core\OssException
     */
    public function actionVideos()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), 'videos');
        $upload->uploadFileName = 'file';
        $upload->verify();
        // 上传
        $url = $upload->save();

        $result = is_array($url) ? $url : ['url' => $url];

        return $result;
    }

    /**
     * 语音上传
     *
     * @return array|mixed|string
     * @throws NotFoundHttpException
     * @throws \OSS\Core\OssException
     */
    public function actionVoices()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), 'voices');
        $upload->uploadFileName = 'file';
        $upload->verify();
        // 上传
        $url = $upload->save();

        $result = is_array($url) ? $url : ['url' => $url];

        return $result;
    }

    /**
     * 文件上传
     *
     * @return array|mixed|string
     * @throws NotFoundHttpException
     * @throws \OSS\Core\OssException
     */
    public function actionFiles()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), 'files');
        $upload->uploadFileName = 'file';
        $upload->verify();
        // 上传
        $url = $upload->save();

        $result = is_array($url) ? $url : ['url' => $url];

        return $result;
    }

    /**
     * base64编码的图片上传
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \OSS\Core\OssException
     */
    public function actionBase64()
    {
        // 保存扩展名称
        $extend = Yii::$app->request->post('extend', 'jpg');

        $upload = new UploadHelper(Yii::$app->request->post(), 'images');
        $upload->uploadFileName = 'file';
        $upload->verify([
            'extension' => $extend,
            'size' => strlen(Yii::$app->request->post('image', '')),
        ]);

        $url =  $upload->save('base64');

        $result = is_array($url) ? $url : ['url' => $url];

        return $result;
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
            return ResultDataHelper::api(404, '找不到文件信息, 合并文件失败');
        }

        UploadHelper::merge($mergeInfo['ultimatelyFilePath'], $mergeInfo['tmpAbsolutePath'], 1, $mergeInfo['extension']);

        Yii::$app->cache->delete('upload-file-guid:' . $guid);

        return [
            'url' => $mergeInfo['relativePath']
        ];
    }
}