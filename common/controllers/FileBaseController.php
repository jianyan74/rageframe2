<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\UploadHelper;
use common\helpers\ResultDataHelper;

/**
 * Class FileBaseController
 * @package common\controllers
 */
class FileBaseController extends Controller
{
    /**
     * 关闭Csrf验证
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * 行为控制
     *
     * @return array
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
            $upload = new UploadHelper(Yii::$app->request->post(), 'images');
            $upload->uploadFileName = 'file';
            $upload->verify();
            // 上传
            $url = $upload->save();

            $result = is_array($url) ? $url : ['url' => $url];
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
            $upload = new UploadHelper(Yii::$app->request->post(), 'files');
            $upload->uploadFileName = 'file';
            $upload->verify();
            // 上传
            $url = $upload->save();

            $result = is_array($url) ? $url : ['url' => $url];
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
            $upload = new UploadHelper(Yii::$app->request->post(), 'videos');
            $upload->uploadFileName = 'file';
            $upload->verify();
            // 上传
            $url = $upload->save();

            $result = is_array($url) ? $url : ['url' => $url];
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
            $upload = new UploadHelper(Yii::$app->request->post(), 'voices');
            $upload->uploadFileName = 'file';
            $upload->verify();
            // 上传
            $url = $upload->save();

            $result = is_array($url) ? $url : ['url' => $url];
            return ResultDataHelper::json(200, '上传成功', $result);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * base64编码的上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBase64()
    {
        try
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
            return ResultDataHelper::json(200, '上传成功', $result);
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

        UploadHelper::merge($mergeInfo['ultimatelyFilePath'], $mergeInfo['tmpAbsolutePath'], 1, $mergeInfo['extension']);

        Yii::$app->cache->delete('upload-file-guid:' . $guid);

        return ResultDataHelper::json(200, '合并完成', [
            'url' => $mergeInfo['relativePath']
        ]);
    }
}