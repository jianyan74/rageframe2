<?php

namespace common\traits;

use Yii;
use yii\web\NotFoundHttpException;
use common\helpers\ResultHelper;
use common\helpers\UploadHelper;
use common\models\common\Attachment;

/**
 * Trait FileActions
 * @package common\traits
 * @author jianyan74 <751393839@qq.com>
 */
trait FileActions
{
    /**
     * 图片上传
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     * @throws \Exception
     */
    public function actionImages()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        $upload->verifyFile();
        $upload->save();

        return $upload->getBaseInfo();
    }

    /**
     * 视频上传
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     * @throws \Exception
     */
    public function actionVideos()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_VIDEOS);
        $upload->verifyFile();
        $upload->save();

        return $upload->getBaseInfo();
    }

    /**
     * 语音上传
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     * @throws \Exception
     */
    public function actionVoices()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_VOICES);
        $upload->verifyFile();
        $upload->save();

        return $upload->getBaseInfo();
    }

    /**
     * 文件上传
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     * @throws \Exception
     */
    public function actionFiles()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_FILES);
        $upload->verifyFile();
        $upload->save();

        return $upload->getBaseInfo();
    }

    /**
     * oss直传配置
     *
     * @return array
     * @throws \Exception
     */
    public function actionOssAccredit()
    {
        // 上传类型
        $type = Yii::$app->request->get('type');
        $typeConfig = Yii::$app->params['uploadConfig'][$type];

        $path = $typeConfig['path'] . date($typeConfig['subName'], time()) . "/";
        $oss = Yii::$app->uploadDrive->oss()->config($typeConfig['maxSize'], $path, 60 * 60 * 2, $type);

        return $oss;
    }

    /**
     * base64编码的图片上传
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionBase64()
    {
        // 保存扩展名称
        $extend = Yii::$app->request->post('extend', 'jpg');
        !in_array($extend, Yii::$app->params['uploadConfig']['images']['extensions']) && $extend = 'jpg';
        $data = Yii::$app->request->post('image', '');

        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        $upload->verifyBase64($data, $extend);
        $upload->save(base64_decode($data));

        return $upload->getBaseInfo();
    }

    /**
     * 根据md5获取文件
     *
     * @return array
     */
    public function actionVerifyMd5()
    {
        $md5 = Yii::$app->request->post('md5');
        if ($file = Yii::$app->services->attachment->findByMd5($md5)) {
            $file['formatter_size'] = Yii::$app->formatter->asShortSize($file['size'], 2);
            $file['url'] = $file['base_url'];
            $file['upload_type'] = UploadHelper::formattingFileType($file['specific_type'], $file['extension'], $file['upload_type']);

            return $file;
        }

        return ResultHelper::json(422, '找不到文件');
    }

    /**
     * 合并
     *
     * @return array|mixed
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Exception
     */
    public function actionMerge()
    {
        $guid = Yii::$app->request->post('guid');
        $mergeInfo = Yii::$app->cache->get(UploadHelper::PREFIX_MERGE_CACHE . $guid);
        if (!$mergeInfo) {
            return ResultHelper::json(404, '找不到文件信息, 合并文件失败');
        }

        $upload = new UploadHelper($mergeInfo['config'], $mergeInfo['type'], true);
        $upload->setPaths($mergeInfo['paths']);
        $upload->setBaseInfo($mergeInfo['baseInfo']);
        $upload->merge();

        Yii::$app->cache->delete('upload-file-guid:' . $guid);

        return $upload->getBaseInfo();
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['index', 'view', 'update', 'create', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}