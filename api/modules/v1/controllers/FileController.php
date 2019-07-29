<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\helpers\UploadHelper;
use common\helpers\ResultDataHelper;
use common\models\common\Attachment;
use api\controllers\OnAuthController;

/**
 * 资源上传控制器
 *
 * Class FileController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class FileController extends OnAuthController
{
    public $modelClass = '';

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
            return ResultDataHelper::api(404, '找不到文件信息, 合并文件失败');
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