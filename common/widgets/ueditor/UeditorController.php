<?php
namespace common\widgets\ueditor;

use common\helpers\ResultHelper;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\ArrayHelper;
use common\helpers\UploadHelper;
use common\enums\StatusEnum;
use yii\helpers\Json;
use addons\Wechat\common\models\Attachment as WechatAttachment;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * 百度编辑器
 *
 * Class UeditorController
 * @package common\widgets\ueditor
 * @author jianyan74 <751393839@qq.com>
 */
class UeditorController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @var array
     */
    public $config = [];

    /**
     * 显示驱动
     *
     * 有Attachment、WechatAttachment、Local
     * @var string
     */
    public $showDrive = 'Attachment';

    /**
     * @var array
     */
    public $actions = [
        'uploadimage' => 'image',
        'uploadscrawl' => 'scrawl',
        'uploadvideo' => 'video',
        'uploadfile' => 'file',
        'listimage' => 'list-image',
        'listfile' => 'list-file',
        'catchimage' => 'catch-image',
        'config' => 'config',
        'listinfo' => 'list-info',
    ];

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
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->config = [
            // server config @see http://fex-team.github.io/ueditor/#server-config
            'scrawlMaxSize' => Yii::$app->params['uploadConfig']['images']['maxSize'],
            'videoMaxSize' => Yii::$app->params['uploadConfig']['videos']['maxSize'],
            'imageMaxSize' => Yii::$app->params['uploadConfig']['images']['maxSize'],
            'fileMaxSize' => Yii::$app->params['uploadConfig']['files']['maxSize'],
            'imageManagerListPath' => Yii::$app->params['uploadConfig']['images']['path'],
            'fileManagerListPath' => Yii::$app->params['uploadConfig']['files']['path'],
            'scrawlFieldName' => 'image',
            'videoFieldName' => 'file',
            'fileFieldName' => 'file',
            'imageFieldName' => 'file',
        ];

        $configPath = Yii::getAlias('@common') . "/widgets/ueditor/";
        // 保留UE默认的配置引入方式
        if (file_exists($configPath . 'config.json')) {
            $config = Json::decode(preg_replace("/\/\*[\s\S]+?\*\//", '', file_get_contents($configPath . 'config.json')));
            $this->config = ArrayHelper::merge($config, $this->config);
        }

        // 设置显示驱动
        $showDrive = Yii::$app->request->get('showDrive');
        if (!empty($showDrive) && in_array($showDrive, ['Attachment', 'WechatAttachment', 'Local'])) {
            $this->showDrive = $showDrive;
        }
    }

    /**
     * 后台统一入口
     *
     * @return array|mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $action = strtolower(Yii::$app->request->get('action', 'config'));
        $actions = $this->actions;
        if (isset($actions[$action])) {
            return $this->run($actions[$action]);
        }

        return $this->result('找不到方法');
    }

    /**
     * 显示配置信息
     */
    public function actionConfig()
    {
        return $this->config;
    }

    /**
     * 上传图片
     *
     * @return array
     */
    public function actionImage()
    {
        try {
            $upload = new UploadHelper(Yii::$app->request->get(), 'images');
            $upload->verifyFile();
            $upload->save();

            $baseInfo = $upload->getBaseInfo();
            return $this->result('SUCCESS', $baseInfo['url']);
        } catch (\Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 上传涂鸦
     *
     * @return array
     */
    public function actionScrawl()
    {
        try {
            // 保存扩展名称
            $extend = Yii::$app->request->post('extend', 'jpg');
            $data = Yii::$app->request->post('image');

            $upload = new UploadHelper(Yii::$app->request->post(), 'images');
            $upload->verifyBase64($data, $extend);
            $upload->save(base64_decode($data));

            $baseInfo = $upload->getBaseInfo();
            return $this->result('SUCCESS', $baseInfo['url']);
        } catch (\Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 上传视频
     *
     * @return array
     */
    public function actionVideo()
    {
        try {
            $upload = new UploadHelper(Yii::$app->request->get(), 'videos');
            $upload->verifyFile();
            $upload->save();

            $baseInfo = $upload->getBaseInfo();
            $url = $baseInfo['url'];
            if (isset($upload->config['poster']) && $upload->config['poster'] == true) {
                $upload->getVideoPoster();
                $baseInfo = $upload->getBaseInfo();
                $posterUrl = $baseInfo['url'];
            } else {
                $posterUrl = '';
            }

            return [
                'state' => 'SUCCESS',
                'url' => $url,
                'posterUrl' => $posterUrl,
            ];
        } catch (\Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 上传文件
     */
    public function actionFile()
    {
        try {
            $upload = new UploadHelper(Yii::$app->request->get(), 'files');
            $upload->verifyFile();
            $upload->save();

            $baseInfo = $upload->getBaseInfo();
            return $this->result('SUCCESS', $baseInfo['url']);
        } catch (\Exception $e) {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 获取远程图片
     *
     * @return array
     * @throws \Exception
     */
    public function actionCatchImage()
    {
        /* 上传配置 */
        $source = Yii::$app->request->post('source', []);
        $upload = new UploadHelper(Yii::$app->request->get(), 'images');
        foreach ($source as $imgUrl) {
            try {
                $upload->save($upload->verifyUrl($imgUrl));
                if ($file = Yii::$app->services->attachment->findByMd5($upload->config['md5'])) {
                    $url = $file['base_url'];
                } else {
                    $baseInfo = $upload->getBaseInfo();
                    $url = $baseInfo['url'];
                }

                $list[] = [
                    'state' => 'SUCCESS',
                    'url' => $url,
                    'source' => $imgUrl
                ];
            } catch (\Exception $e) {
                $list[] = [
                    'state' => $e->getMessage(),
                    'url' => '',
                    'source' => $imgUrl
                ];
            }
        }

        /* 返回抓取数据 */
        return [
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list' => $list
        ];
    }

    /**
     * 文件列表
     *
     * @return array
     */
    public function actionListFile()
    {
        $prefix = Yii::$app->params['uploadConfig']['files']['fullPath'] == true ? Yii::$app->request->hostInfo : '';
        $action = 'get' . $this->showDrive;
        return $this->$action(
            $this->config['fileManagerListSize'],
            $this->config['fileManagerListPath'],
            $prefix
        );
    }

    /**
     * 图片列表
     *
     * @return array
     */
    public function actionListImage()
    {
        $prefix = Yii::$app->params['uploadConfig']['images']['fullPath'] == true ? Yii::$app->request->hostInfo : '';
        $action = 'get' . $this->showDrive;
        return $this->$action(
            $this->config['imageManagerListSize'],
            $this->config['imageManagerListPath'],
            $prefix
        );
    }

    /**
     * 获取微信资源
     *
     * @param $size
     * @param $path
     * @return array
     */
    public function getWechatAttachment($size, $path)
    {
        $start = Yii::$app->request->get('start');

        $data = WechatAttachment::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['media_type' => 'image'])
            ->orderBy('id desc');
        $countModel = clone $data;
        $models = $data->offset($start)
            ->limit($size)
            ->asArray()
            ->all();

        $files = [];
        foreach ($models as $model) {
            $files[] = [
                'url' => urldecode(Url::to(['addons/rf-wechat/analysis/image', 'attach' => $model['media_url']])),
                'mtime' => $model['created_at']
            ];
        }

        return [
            'state' => 'SUCCESS',
            'list' => $files,
            'start' => $start,
            'total' => $countModel->count(),
        ];
    }

    /**
     * 获取数据库资源文件列表
     *
     * @param $size
     * @param $path
     * @return array
     */
    public function getAttachment($size, $path)
    {
        $start = Yii::$app->request->get('start');
        $upload_type = $path == $this->config['imageManagerListPath'] ? 'images' : 'files';
        list($files, $total) = Yii::$app->services->attachment->getBaiduListPage($upload_type, $start, $size);

        return [
            'state' => 'SUCCESS',
            'list' => $files,
            'start' => $start,
            'total' => $total,
        ];
    }

    /**
     * 文件和图片管理action使用
     *
     * @param $allowFiles
     * @param $listSize
     * @param $path
     * @return array
     */
    protected function getLocal($listSize, $path, $prefix)
    {
        /* 获取参数 */
        $size = Yii::$app->request->get('size', $listSize);
        $this->fileStart = Yii::$app->request->get('start', 0);
        $this->fileEnd = $this->fileStart + $size;

        $files = $this->getLocalFiles($path, $prefix);
        return  [
            'state' => 'SUCCESS',
            'list' => $files,
            'start' => $this->fileStart,
            'total' => $this->fileNum,
        ];
    }

    /**
     * @param string $path 文件路径
     * @param string $allowFiles 文件后缀
     * @param array $files 文件列表
     * @param string $prefix 前缀
     * @return array
     */
    public function getLocalFiles($path, $prefix, &$files = [])
    {
        if (!$this->filesystem) {
            $adapter = new Local(Yii::getAlias('@attachment'));
            $this->filesystem = new Filesystem($adapter);
        }

        $listFiles = $this->filesystem->listContents($path);
        foreach ($listFiles as $key => $listFile)
        {
            if ($listFile['type'] == 'dir') {
                $this->getLocalFiles($listFile['path'], $prefix, $files);
            } else {
                // 获取选中列表
                if ($this->fileNum >= $this->fileStart && $this->fileNum < $this->fileEnd) {
                    $url = $prefix . Yii::getAlias('@attachurl') . '/' . $listFile['path'];
                    $files[] = [
                        'url' => $url,
                        'mtime' => $listFile['timestamp']
                    ];
                }

                $this->fileNum++;
            }

            unset($listFiles[$key]);
        }

        return $files;
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    protected function result($state = 'ERROR', $url = '')
    {
        return [
            "state" => $state,
            "url" => $url,
        ];
    }
}