<?php
namespace common\widgets\ueditor;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\ArrayHelper;
use common\helpers\UploadHelper;
use common\helpers\StringHelper;

/**
 * 百度编辑器控制器
 *
 * Class UeditorController
 * @package backend\controllers
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
     * 列出文件/图片时需要忽略的文件夹
     * 主要用于处理缩略图管理，兼容比如elFinder之类的程序
     * @var array
     */
    public $ignoreDir = [
        '.thumbnails'
    ];

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
        if (file_exists($configPath . 'config.json'))
        {
            $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", '', file_get_contents($configPath . 'config.json')), true);
            $this->config = ArrayHelper::merge($config, $this->config);
        }
    }

    /**
     * 后台统一入口
     *
     * @return array|mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

        $action = strtolower(Yii::$app->request->get('action', 'config'));
        $actions = $this->actions;
        if (isset($actions[$action]))
        {
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
        try
        {
            $upload = new UploadHelper(Yii::$app->request->get(), 'images');
            $upload->uploadFileName = 'file';
            $upload->verify();
            // 上传
            $url = $upload->save();

            return $this->result('SUCCESS', $url);
        }
        catch (\Exception $e)
        {
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
        try
        {
            // 保存扩展名称
            $extend = Yii::$app->request->post('extend', 'jpg');
            $data = Yii::$app->request->post('image');

            $upload = new UploadHelper(Yii::$app->request->post(), 'images');
            $upload->uploadFileName = 'file';
            $upload->verify([
                'extension' => $extend,
                'size' => strlen($data),
            ]);

            $url = $upload->save('base64');
            return $this->result('SUCCESS', $url);
        }
        catch (\Exception $e)
        {
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
        try
        {
            $upload = new UploadHelper(Yii::$app->request->get(), 'videos');
            $upload->uploadFileName = 'file';
            $upload->verify();
            // 上传
            $url = $upload->save();

            return $this->result('SUCCESS', $url);
        }
        catch (\Exception $e)
        {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 上传文件
     */
    public function actionFile()
    {
        try
        {
            $upload = new UploadHelper(Yii::$app->request->get(), 'files');
            $upload->uploadFileName = 'file';
            $upload->verify();
            // 上传
            $url = $upload->save();

            return $this->result('SUCCESS', $url);
        }
        catch (\Exception $e)
        {
            return $this->result($e->getMessage());
        }
    }

    /**
     * 获取远程图片
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCatchImage()
    {
        /* 上传配置 */
        $source = Yii::$app->request->post('source', []);

        $upload = new UploadHelper(Yii::$app->request->get(), 'images');
        $upload->uploadFileName = 'file';

        foreach ($source as $imgUrl)
        {
            try
            {
                $upload->verifyRemote($imgUrl);
                // 上传
                $url = $upload->save('remote');

                $list[] = [
                    'state' => 'SUCCESS',
                    'url' => $url,
                    'source' => $imgUrl
                ];
            }
            catch (\Exception $e)
            {
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
        return $this->manage(
            $this->config['fileManagerAllowFiles'],
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
        return $this->manage(
            $this->config['imageManagerAllowFiles'],
            $this->config['imageManagerListSize'],
            $this->config['imageManagerListPath'],
            $prefix
        );
    }

    /**
     * 文件和图片管理action使用
     *
     * @param $allowFiles
     * @param $listSize
     * @param $path
     * @return array
     */
    protected function manage($allowFiles, $listSize, $path, $prefix)
    {
        $allowFiles = substr(str_replace('.', '|', join('', $allowFiles)), 1);
        /* 获取参数 */
        $size = isset($_GET['size']) ? $_GET['size'] : $listSize;
        $start = isset($_GET['start']) ? $_GET['start'] : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = Yii::getAlias('@attachment') . (substr($path, 0, 1) == '/' ? '' : '/') . $path;

        $files = [];
        $files = $this->getFiles($path, $allowFiles, $files,$prefix);
        if (!count($files))
        {
            return  [
                'state' => 'no match file',
                'list' => [],
                'start' => $start,
                'total' => count($files),
            ];
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = []; $i < $len && $i >= 0 && $i >= $start; $i--)
        {
            $list[] = $files[$i];
        }

        /* 返回数据 */
        return [
            'state' => 'SUCCESS',
            'list' => $list,
            'start' => $start,
            'total' => count($files),
        ];
    }

    /**
     * 遍历获取目录下的指定类型的文件
     *
     * @param $path
     * @param $allowFiles
     * @param array $files
     * @return array|null
     */
    protected function getFiles($path, $allowFiles, &$files = [], $prefix)
    {
        if (!is_dir($path) || in_array(basename($path), $this->ignoreDir))
        {
            return null;
        }

        if (substr($path, strlen($path) - 1) != '/')
        {
            $path .= '/';
        }

        $handle = opendir($path);
        while (false !== ($file = readdir($handle)))
        {
            if ($file != '.' && $file != '..')
            {
                $childPath = $path . $file;
                if (is_dir($childPath))
                {
                    $this->getFiles($childPath, $allowFiles, $files, $prefix);
                }
                else
                {
                    // 正则匹配文件后缀待优化
                    $pat = "/\.(" . $allowFiles . ")$/i";
                    if ($this->action->id == 'list-image')
                    {
                        $pat = "/\.thumbnail\.(" . $allowFiles . ")$/i";
                    }

                    $url = Yii::getAlias('@attachurl') . substr($childPath, strlen(Yii::getAlias('@attachment')));
                    $url = StringHelper::iconvForWindows($url, "utf-8");

                    $files[] = [
                        'url' => $prefix . $url,
                        'mtime' => filemtime($childPath)
                    ];

//                    if (preg_match($pat, $file))
//                    {
//
//                    }
                }
            }
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