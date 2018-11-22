<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\UploadHelper;
use common\helpers\ResultDataHelper;

/**
 * 文件上传控制器
 *
 * Class FileBaseController
 * @package common\controllers
 */
class FileBaseController extends Controller
{
    /**
     * 关闭csrf验证
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * 行为控制
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * 图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImages()
    {
        try
        {
            // 载入配置信息
            UploadHelper::load(Yii::$app->request->post(), 'images');
            // 上传
            $result = UploadHelper::file();

            return ResultDataHelper::json(200, '上传成功', $result);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 视频上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVideos()
    {
        try
        {
            // 载入配置信息
            UploadHelper::load(Yii::$app->request->post(), 'videos');
            // 上传
            $result = UploadHelper::file();

            return ResultDataHelper::json(200, '上传成功', $result);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 语音上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionVoices()
    {
        try
        {
            // 载入配置信息
            UploadHelper::load(Yii::$app->request->post(), 'voices');
            // 上传
            $result = UploadHelper::file();

            return ResultDataHelper::json(200, '上传成功', $result);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 文件上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFiles()
    {
        try
        {
            // 载入配置信息
            UploadHelper::load(Yii::$app->request->post(), 'files');
            // 上传
            $result = UploadHelper::file();
            return ResultDataHelper::json(200, '上传成功', $result);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * base64编码的图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBase64Img()
    {
        try
        {
            $base64Data = Yii::$app->request->post('image');
            $extend = Yii::$app->request->post('extend', 'jpg');
            return ResultDataHelper::json(200, '上传成功', UploadHelper::Base64Img($base64Data, $extend));
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 七牛云存储
     *
     * @return array
     * @throws \Exception
     */
    public function actionQiniu()
    {
        try
        {
            return ResultDataHelper::json(200, '上传成功', UploadHelper::qiniu($_FILES['file']));
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 阿里云OSS上传
     *
     * @return array
     * @throws \Exception
     */
    public function actionOss()
    {
        try
        {
            return ResultDataHelper::json(200, '上传成功', UploadHelper::oss($_FILES['file']));
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 合并
     *
     * @return array
     */
    public function actionMerge()
    {
        $guid = Yii::$app->request->post('guid');
        $mergeInfo = Yii::$app->cache->get(UploadHelper::$prefixForMergeCache . $guid);

        if (!$mergeInfo)
        {
            return ResultDataHelper::json(404, '找不到文件信息, 合并文件失败');
        }

        UploadHelper::mergeFile($mergeInfo['ultimatelyFilePath'], $mergeInfo['tmpAbsolutePath'], 1, $mergeInfo['extension']);

        Yii::$app->cache->delete('upload-file-guid:' . $guid);

        return ResultDataHelper::json(200, '合并完成', [
            'urlPath' => $mergeInfo['relativePath']
        ]);
    }
}