<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\FileHelper;
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
     * 总切片大小
     *
     * @var
     */
    public $chunks;

    /**
     * 当前切片
     *
     * @var
     */
    public $chunk;

    /**
     * 切片唯一ID
     *
     * @var
     */
    public $guid;

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
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->chunks = Yii::$app->request->post('chunks', null);
        $this->chunk = Yii::$app->request->post('chunk');
        $this->guid = Yii::$app->request->post('guid', null);

        parent::init();
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
            // 判断是否有切片上传直接接管
            if ($this->chunks && $this->guid)
            {
                return ResultDataHelper::result(200, '上传成功', UploadHelper::chunks($this->guid, $this->chunk, $this->chunks, 'file', 'images'));
            }

            // 上传
            $result = UploadHelper::upload('file', 'images');
            // 创建缩略图
            $thumbWidget = Yii::$app->request->post('thumbWidget', null);
            $thumbHeight = Yii::$app->request->post('thumbHeight', null);
            if ($thumbWidget && $thumbHeight && FileHelper::mkdirs($result['thumbAbsolutePath']))
            {
                UploadHelper::createThumb($result['absolutePath'] . $result['name'], $result['thumbAbsolutePath'] . $result['name'], $thumbWidget, $thumbHeight);
            }

            return ResultDataHelper::result(200, '上传成功', [
                'urlPath' => $result['relativePath'] . $result['name'],
                'thumbUrlPath' => $result['thumbRelativePath'] . $result['name'],
            ]);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::result(404, $e->getMessage());
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
            // 判断是否有切片上传直接接管
            if ($this->chunks && $this->guid)
            {
                return ResultDataHelper::result(200, '上传成功', UploadHelper::chunks($this->guid, $this->chunk, $this->chunks, 'file', 'videos'));
            }

            $result = UploadHelper::upload('file', 'videos');
            return ResultDataHelper::result(200, '上传成功', [
                'urlPath' => $result['relativePath'] . $result['name'],
            ]);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::result(404, $e->getMessage());
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
            if ($this->chunks && $this->guid)
            {
                return ResultDataHelper::result(200, '上传成功', UploadHelper::chunks($this->guid, $this->chunk, $this->chunks, 'file', 'voices'));
            }

            $result = UploadHelper::upload('file', 'voices');
            return ResultDataHelper::result(200, '上传成功', [
                'urlPath' => $result['relativePath'] . $result['name'],
            ]);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::result(404, $e->getMessage());
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
            if ($this->chunks && $this->guid)
            {
                return ResultDataHelper::result(200, '上传成功', UploadHelper::chunks($this->guid, $this->chunk, $this->chunks, 'file', 'files'));
            }

            $result = UploadHelper::upload('file', 'files');
            return ResultDataHelper::result(200, '上传成功', [
                'urlPath' => $result['relativePath'] . $result['name'],
            ]);
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::result(404, $e->getMessage());
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
            return ResultDataHelper::result(200, '上传成功', UploadHelper::Base64Img(Yii::$app->request->post('image')));
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::result(404, $e->getMessage());
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
            return ResultDataHelper::result(200, '上传成功', UploadHelper::qiniu($_FILES['file']));
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::result(404, $e->getMessage());
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
            return ResultDataHelper::result(200, '上传成功', UploadHelper::oss($_FILES['file']));
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::result(404, $e->getMessage());
        }
    }
}