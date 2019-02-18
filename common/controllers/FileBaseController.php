<?php
namespace common\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\UploadHelper;
use common\helpers\ResultDataHelper;
use common\enums\StatusEnum;
use common\models\common\Attachment;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

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
        try
        {
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
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
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_FILES);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
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
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_VIDEOS);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
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
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_VOICES);
            $upload->verifyFile();
            $upload->save();

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
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
     */
    public function actionBase64()
    {
        try
        {
            // 保存扩展名称
            $extend = Yii::$app->request->post('extend', 'jpg');
            !in_array($extend, Yii::$app->params['uploadConfig']['images']['extensions']) && $extend = 'jpg';
            $data = Yii::$app->request->post('image', '');

            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
            $upload->verifyBase64($data, $extend);
            $upload->save(base64_decode($data));

            return ResultDataHelper::json(200, '上传成功', $upload->getBaseInfo());
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
        $mergeInfo = Yii::$app->cache->get(UploadHelper::PREFIX_MERGE_CACHE . $guid);

        if (!$mergeInfo)
        {
            return ResultDataHelper::json(404, '找不到文件信息, 合并文件失败');
        }

        try
        {
            $upload = new UploadHelper($mergeInfo['config'], $mergeInfo['type']);
            $upload->setPaths($mergeInfo['paths']);
            $upload->setBaseInfo($mergeInfo['baseInfo']);
            $upload->merge();

            Yii::$app->cache->delete('upload-file-guid:' . $guid);

            return ResultDataHelper::json(200, '合并完成', $upload->getBaseInfo());
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }
    }

    /**
     * 获取资源列表
     *
     * @return string
     */
    public function actionAttachment()
    {
        $upload_type = Yii::$app->request->get('upload_type', Attachment::UPLOAD_TYPE_IMAGES);

        $data = Attachment::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['upload_type' => $upload_type]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        $year = [];
        for ($i = 2019; $i <= date('Y'); $i++)
        {
            $year[$i] = $i;
        }

        $month = [];
        for ($i = 1; $i <= 12; $i++)
        {
            $month[$i] = $i;
        }

        // 如果是以文件形式上传的图片手动修改为图片类型显示
        foreach ($models as &$model)
        {
            if (preg_match("/^image/", $model['specific_type']) && $model['extension'] != 'psd')
            {
                $model['upload_type'] = Attachment::UPLOAD_TYPE_IMAGES;
            }
        }

        return $this->renderAjax('@common/widgets/webuploader/views/ajax-list/list', [
            'models' => $models,
            'upload_type' => $upload_type,
            'month' => $month,
            'year' => $year,
            'boxId' => Yii::$app->request->get('boxId'),
            'multiple' => Yii::$app->request->get('multiple'),
        ]);
    }

    /**
     * @return array
     */
    public function actionAjaxAttachment()
    {
        $upload_type = Yii::$app->request->get('upload_type', Attachment::UPLOAD_TYPE_IMAGES);
        $year = Yii::$app->request->get('year', '');
        $month = Yii::$app->request->get('month', '');

        $data = Attachment::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['upload_type' => $upload_type])
            ->andFilterWhere(['year' => $year])
            ->andFilterWhere(['month' => $month]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        // 如果是以文件形式上传的图片手动修改为图片类型显示
        foreach ($models as &$model)
        {
            if (preg_match("/^image/", $model['specific_type']))
            {
                $model['upload_type'] = Attachment::UPLOAD_TYPE_IMAGES;
            }
        }

        return ResultDataHelper::json(200, '获取成功', $models);
    }

    /**
     * 获取本地文件列表
     *
     * @return array
     */
    protected function actionLocal()
    {
        /* 获取参数 */
        $path = Yii::$app->request->get('path', 'images');
        $year = Yii::$app->request->get('year', date('Y'));
        $month = Yii::$app->request->get('month', date('m'));
        $path = $path . '/' . $year . '/' . $month;
        $size = Yii::$app->request->get('size', 20);
        $this->fileStart = Yii::$app->request->get('start', 0);
        $this->fileEnd = $this->fileStart + $size;
        /* 设置驱动 */
        $adapter = new Local(Yii::getAlias('@attachment'));
        $this->filesystem = new Filesystem($adapter);

        $prefix = Yii::$app->params['uploadConfig'][$path]['fullPath'] == true ? Yii::$app->request->hostInfo : '';
        $files = $this->getLocalList($path, $prefix);

        return ResultDataHelper::json(200, '获取成功', [
            'list' => $files,
            'start' => $this->fileStart,
            'total' => count($files),
        ]);
    }

    /**
     * 根据目录获取文件列表
     *
     * @param string $path 文件路径
     * @param string $allowFiles 文件后缀
     * @param array $files 文件列表
     * @param string $prefix 前缀
     * @return array
     */
    protected function getLocalList($path, $prefix, &$files = [])
    {
        $listFiles = $this->filesystem->listContents($path);
        foreach ($listFiles as $key => &$listFile)
        {
            if ($listFile['type'] == 'dir')
            {
                $this->getLocalList($listFile['path'], $prefix, $files);
            }
            else
            {
                // 获取选中列表
                if ($this->fileNum >= $this->fileStart && $this->fileNum < $this->fileEnd)
                {
                    $listFile['path'] = $prefix . Yii::getAlias('@attachurl') . '/' . $listFile['path'];
                    $files[] = $listFile;
                }

                $this->fileNum++;
            }

            unset($listFiles[$key]);
        }

        return $files;
    }
}