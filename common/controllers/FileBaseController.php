<?php

namespace common\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\ArrayHelper;
use common\helpers\UploadHelper;
use common\helpers\ResultDataHelper;
use common\models\common\Attachment;
use common\components\UploadDrive;

/**
 * Class FileBaseController
 * @package common\controllers
 * @author jianyan74 <751393839@qq.com>
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
     * @var int
     */
    protected $fileStart;

    /**
     * @var int
     */
    protected $fileEnd;

    /**
     * @var int
     */
    protected $fileNum = 0;

    /**
     * @var \League\Flysystem\Adapter\Local
     */
    protected $filesystem;

    /**
     * 行为控制
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
        try {
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
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
        try {
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_FILES);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
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
        try {
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_VIDEOS);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
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
        try {
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_VOICES);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * base64编码的上传
     *
     * @return array
     */
    public function actionBase64()
    {
        try {
            // 保存扩展名称
            $extend = Yii::$app->request->post('extend', 'jpg');
            !in_array($extend, Yii::$app->params['uploadConfig']['images']['extensions']) && $extend = 'jpg';
            $data = Yii::$app->request->post('image', '');

            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
            $upload->verifyBase64($data, $extend);
            $upload->save(base64_decode($data));

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
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
        $mergeInfo = Yii::$app->cache->get(UploadHelper::PREFIX_MERGE_CACHE . $guid);

        if (!$mergeInfo) {
            return ResultDataHelper::json(404, '找不到文件信息, 合并文件失败');
        }

        try {
            $upload = new UploadHelper($mergeInfo['config'], $mergeInfo['type'], true);
            $upload->setPaths($mergeInfo['paths']);
            $upload->setBaseInfo($mergeInfo['baseInfo']);
            $upload->merge();

            Yii::$app->cache->delete('upload-file-guid:' . $guid);

            return ResultDataHelper::json(200, '合并完成', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 获取oss信息
     *
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     */
    public function actionGetOssPath()
    {
        $url = Yii::$app->request->post('url');
        $urlArr = parse_url($url);
        $base_url = $urlArr['path'];
        $drive = new UploadDrive(Attachment::DRIVE_OSS);
        $filesystem = $drive->getEntity();

        if (!$filesystem->has($base_url)) {
            return ResultDataHelper::json(404, '找不到文件');
        }

        $metadata = $filesystem->getMetadata($base_url);
        $path = parse_url($metadata['info']['url'])['path'];

        $baseUrlArr = explode('/', $base_url);
        $fileName = end($baseUrlArr);
        $fileName = explode('.', $fileName);
        $extension = end($fileName);
        unset($fileName[count($fileName) - 1]);
        $name = implode('.', $fileName);

        $baseInfo = [
            'drive' => Attachment::DRIVE_OSS,
            'upload_type' => Yii::$app->request->post('type'),
            'specific_type' => $metadata['content-type'],
            'size' => $metadata['content-length'],
            'extension' => $extension,
            'name' => $name,
            'base_url' => $metadata['info']['url'],
            'path' => $path
        ];

        // 写入数据库
        $attachment_id = Yii::$app->services->attachment->create($baseInfo);

        $baseInfo['url'] = $baseInfo['base_url'];
        $baseInfo['id'] = $attachment_id;
        $baseInfo['upload_type'] = UploadHelper::formattingFileType($baseInfo['specific_type'], $baseInfo['extension'], $baseInfo['upload_type']);
        $baseInfo['formatter_size'] = Yii::$app->formatter->asShortSize($baseInfo['size'], 2);

        return ResultDataHelper::json(200, '获取成功', $baseInfo);
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

            return ResultDataHelper::json(200, '获取成功', $file);
        }

        return ResultDataHelper::json(404, '找不到文件');
    }

    /**
     * 资源选择器
     *
     * @param bool $json
     * @return array|string
     */
    public function actionSelector($json = false)
    {
        $upload_type = Yii::$app->request->get('upload_type', Attachment::UPLOAD_TYPE_IMAGES);
        $year = Yii::$app->request->get('year', '');
        $month = Yii::$app->request->get('month', '');
        $keyword = Yii::$app->request->get('keyword', '');
        $drive = Yii::$app->request->get('drive', '');
        list($models, $pages) = Yii::$app->services->attachment->getListPage($upload_type, $drive, $year, $month, $keyword);

        // 判断是否直接返回json格式
        if ($json == true) {
            return ResultDataHelper::json(200, '获取成功', $models);
        }

        return $this->renderAjax('@common/widgets/webuploader/views/selector', [
            'models' => Json::encode($models),
            'pages' => $pages,
            'upload_type' => $upload_type,
            'multiple' => Yii::$app->request->get('multiple', true),
            'upload_drive' => Yii::$app->request->get('upload_drive', Attachment::DRIVE_LOCAL),
            'drives' => Attachment::$driveExplain,
            'year' => ArrayHelper::numBetween(2019, date('Y')),
            'month' => ArrayHelper::numBetween(1, 12),
            'boxId' => Yii::$app->request->get('boxId'),
        ]);
    }
}