<?php

namespace common\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use common\helpers\ArrayHelper;
use common\helpers\UploadHelper;
use common\helpers\ResultHelper;
use common\models\common\Attachment;

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

            return ResultHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
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

            return ResultHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
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

            return ResultHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
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

            return ResultHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    /**
     * Markdown 图片上传
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImagesMarkdown()
    {
        try {
            $upload = new UploadHelper(Yii::$app->request->get(), Attachment::UPLOAD_TYPE_IMAGES);
            $upload->uploadFileName = 'editormd-image-file';
            $upload->verifyFile();
            $upload->save();

            $info = $upload->getBaseInfo();

            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'success' => 1,
                'url' => $info['url'],
            ];
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
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

            return ResultHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
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
            return ResultHelper::json(404, '找不到文件信息, 合并文件失败');
        }

        try {
            $upload = new UploadHelper($mergeInfo['config'], $mergeInfo['type'], true);
            $upload->setPaths($mergeInfo['paths']);
            $upload->setBaseInfo($mergeInfo['baseInfo']);
            $upload->merge();

            Yii::$app->cache->delete('upload-file-guid:' . $guid);

            return ResultHelper::json(200, '合并完成', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
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

        return ResultHelper::json(200, '获取成功', $oss);
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

            return ResultHelper::json(200, '获取成功', $file);
        }

        return ResultHelper::json(404, '找不到文件');
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
            return ResultHelper::json(200, '获取成功', $models);
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