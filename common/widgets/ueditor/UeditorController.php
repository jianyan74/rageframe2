<?php
namespace common\widgets\ueditor;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\ArrayHelper;
use common\helpers\UploadHelper;
use common\helpers\StringHelper;
use Imagine\Imagick\Image;

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
     * 缩略图设置
     * 默认不开启
     * ['height' => 200, 'width' => 200]表示生成200*200的缩略图，如果设置为空数组则不生成缩略图
     * @var array
     */
    public $thumbnail = [];

    /**
     * 图片缩放设置
     * 默认不缩放。
     * 配置如 ['height'=>200,'width'=>200]
     * @var array
     */
    public $zoom = [];

    /**
     * 水印设置
     * 参考配置如下：
     * ['path'=>'水印图片位置','position'=>0]
     * 默认位置为 9，可不配置
     * position in [1 ,9]，表示从左上到右下的9个位置。
     * @var array
     */
    public $watermark = [];

    /**
     * 是否允许内网采集
     * 如果为 false 则远程图片获取不获取内网图片，防止 SSRF。
     * 默认为 false
     * @var bool
     */
    public $allowIntranet = false;

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
        ];

        $configPath = Yii::getAlias('@common') . "/widgets/ueditor/";
        // 保留UE默认的配置引入方式
        if (file_exists($configPath . 'config.json'))
        {
            $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", '', file_get_contents($configPath . 'config.json')), true);
            $this->config = ArrayHelper::merge($config, $this->config);
        }

        if (!is_array($this->thumbnail))
        {
            $this->thumbnail = false;
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
        $actions = [
            'uploadimage' => 'image',
            'uploadscrawl' => 'scrawl',
            'uploadvideo' => 'video',
            'uploadfile' => 'file',
            'listimage' => 'list-image',
            'listfile' => 'list-file',
            'catchimage' => 'catch-image',
            'config' => 'config',
            'listinfo' => 'list-info'
        ];

        if (isset($actions[$action]))
        {
            return $this->run($actions[$action]);
        }

        return [
            'state' => '找不到方法'
        ];
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
            UploadHelper::load([], 'images', 'upfile');
            $result = UploadHelper::file();

            return $this->result('SUCCESS', $result['urlPath']);
        }
        catch (\Exception $e)
        {
            return $this->result();
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
            $resUpload = UploadHelper::Base64Img(Yii::$app->request->post('upfile'));
            return $this->result('SUCCESS', $resUpload['urlPath']);
        }
        catch (\Exception $e)
        {
            return $this->result();
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
            UploadHelper::load([], 'videos', 'upfile');
            $result = UploadHelper::file();

            return $this->result('SUCCESS', $result['urlPath']);
        }
        catch (\Exception $e)
        {
            return [
                "state" => 'ERROR',
                "url" => '',
                "message" => $e->getMessage(),
            ];
        }
    }

    /**
     * 上传文件
     */
    public function actionFile()
    {
        try
        {
            UploadHelper::load([], 'files', 'upfile');
            $result = UploadHelper::file();

            return $this->result('SUCCESS', $result['urlPath']);
        }
        catch (\Exception $e)
        {
            return $this->result();
        }
    }

    /**
     * 获取远程图片
     */
    public function actionCatchImage()
    {
        /* 上传配置 */
        $config = [
            'pathFormat' => $this->config['catcherPathFormat'],
            'maxSize' => $this->config['catcherMaxSize'],
            'allowFiles' => $this->config['catcherAllowFiles'],
            'oriName' => 'remote.png'
        ];

        $fieldName = $this->config['catcherFieldName'];
        /* 抓取远程图片 */
        $list = [];
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, 'remote');
            if ($this->allowIntranet)
                $item->setAllowIntranet(true);
            $info = $item->getFileInfo();
            $info['thumbnail'] = $this->imageHandle($info['url']);
            $list[] = [
                'state' => $info['state'],
                'url' => $info['url'],
                'source' => $imgUrl
            ];
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
                    $url = StringHelper::deCodeIconvForWindows($url);

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

    /**
     * 自动处理图片
     *
     * @param $file
     * @return string
     */
    protected function imageHandle($file)
    {
        if (substr($file, 0, 1) != '/')
        {
            $file = '/' . $file;
        }

        //先处理缩略图
        if ($this->thumbnail && !empty($this->thumbnail['height']) && !empty($this->thumbnail['width']))
        {
            $file_path = pathinfo($file);
            $thumbnailFile = $file_path['dirname'] . '/' . $file_path['filename'] . '.thumbnail.' . $file_path['extension'];
            Image::thumbnail($this->webroot . $file, intval($this->thumbnail['width']), intval($this->thumbnail['height']))
                ->save($this->webroot . $thumbnailFile);
        }
        //再处理缩放，默认不缩放
        //...缩放效果非常差劲-，-
        if (isset($this->zoom['height']) && isset($this->zoom['width']))
        {
            $size = $this->getSize($this->webroot . $file);
            if ($size && $size[0] > 0 && $size[1] > 0) {
                $ratio = min([$this->zoom['height'] / $size[0], $this->zoom['width'] / $size[1], 1]);
                Image::thumbnail($this->webroot . $file, ceil($size[0] * $ratio), ceil($size[1] * $ratio))
                    ->save($this->webroot . $file);
            }
        }

        //最后生成水印
        if (isset($this->watermark['path']) && file_exists($this->watermark['path']))
        {
            if (!isset($this->watermark['position']) or $this->watermark['position'] > 9 or $this->watermark['position'] < 0 or !is_numeric($this->watermark['position']))
                $this->watermark['position'] = 9;
            $size = $this->getSize($this->webroot . $file);
            $waterSize = $this->getSize($this->watermark['path']);
            if ($size[0] > $waterSize[0] and $size[1] > $waterSize[1])
            {
                $halfX = $size[0] / 2;
                $halfY = $size[1] / 2;
                $halfWaterX = $waterSize[0] / 2;
                $halfWaterY = $waterSize[1] / 2;
                switch (intval($this->watermark['position']))
                {
                    case 1:
                        $x = 0;
                        $y = 0;
                        break;
                    case 2:
                        $x = $halfX - $halfWaterX;
                        $y = 0;
                        break;
                    case 3:
                        $x = $size[0] - $waterSize[0];
                        $y = 0;
                        break;
                    case 4:
                        $x = 0;
                        $y = $halfY - $halfWaterY;
                        break;
                    case 5:
                        $x = $halfX - $halfWaterX;
                        $y = $halfY - $halfWaterY;
                        break;
                    case 6:
                        $x = $size[0] - $waterSize[0];
                        $y = $halfY - $halfWaterY;
                        break;
                    case 7:
                        $x = 0;
                        $y = $size[1] - $waterSize[1];
                        break;
                    case 8:
                        $x = $halfX - $halfWaterX;
                        $y = $size[1] - $waterSize[1];
                        break;
                    case 9:
                    default:
                        $x = $size[0] - $waterSize[0];
                        $y = $size[1] - $waterSize[1];
                }

                Image::watermark($this->webroot . $file, $this->watermark['path'], [$x, $y])
                    ->save($this->webroot . $file);
            }
        }

        return $file;
    }

    /**
     * 获取图片的大小
     * 主要用于获取图片大小并
     * @param $file
     * @return array
     */
    protected function getSize($file)
    {
        if (!file_exists($file))
        {
            return [];
        }

        $info = pathinfo($file);
        $image = null;
        switch (strtolower($info['extension']))
        {
            case 'gif':
                $image = imagecreatefromgif($file);
                break;
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file);
                break;
            case 'png':
                $image = imagecreatefrompng($file);
                break;
            default:
                break;
        }

        if ($image == null)
        {
            return [];
        }

        return [imagesx($image), imagesy($image)];
    }
}