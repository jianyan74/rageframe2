<?php
namespace common\helpers;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use crazyfd\qiniu\Qiniu;
use OSS\OssClient;

/**
 * Class UploadHelper
 * @package common\helpers
 */
class UploadHelper
{
    /**
     * 上传配置
     *
     * @var array
     */
    public $config = [];

    /**
     * 上传路径
     *
     * @var array
     */
    public $paths = [];

    /**
     * 上传文件基础信息
     *
     * @var array
     */
    public $fileBaseInfo = [];

    /**
     * Yii2 上传类
     *
     * @var object
     */
    public $uploadedFile;

    /**
     * $_File 名称
     *
     * @var string
     */
    public $uploadFileName = 'file';

    /**
     * 上传类型
     *
     * @var string
     */
    public $type = 'image';

    /**
     * 接管上传方法
     *
     * @var
     */
    public $takeOverAction = 'local';

    /**
     * 文件名称
     *
     * @var
     */
    public $fileName;

    public static $prefixForMergeCache = 'upload-file-guid:';

    public function  __construct(array $config, $type)
    {
        // 解密json
        foreach ($config as &$item)
        {
            if (!empty($item) && !is_numeric($item) && !is_array($item))
            {
                !empty(json_decode($item)) && $item = json_decode($item, true);
            }
        }

        $this->config = ArrayHelper::merge(Yii::$app->params['uploadConfig'][$type], $config);
        !empty($this->config['takeOverAction']) && $this->takeOverAction = $this->config['takeOverAction'];
        $this->type = $type;
    }

    /**
     * 验证是否符合上传
     *
     * @param $fileBaseInfo
     *  Array(
     *   [name] => temp.jpg
     *   [size] => 300939
     * )
     * @throws NotFoundHttpException
     */
    public function verify($fileBaseInfo = [])
    {
        if (empty($fileBaseInfo))
        {
            $uploadedFile = UploadedFile::getInstanceByName($this->uploadFileName);
            $fileBaseInfo = [
                'size' => $uploadedFile->size,
                'name' => $uploadedFile->getBaseName(),
                'extension' => $uploadedFile->getExtension(),
            ];

            $this->uploadedFile = $uploadedFile;
        }

        if ($fileBaseInfo['size'] > $this->config['maxSize'])
        {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        if (!empty($this->config['extensions']) && !in_array($fileBaseInfo['extension'], $this->config['extensions']))
        {
            throw new NotFoundHttpException('文件类型不允许');
        }

        $this->fileBaseInfo = $fileBaseInfo;
        unset($uploadedFile, $uploadedFile);
        return true;
    }

    /**
     * 验证是否符合上传
     *
     * @param $fileBaseInfo
     *  Array(
     *   [name] => temp.jpg
     *   [size] => 300939
     * )
     * @throws NotFoundHttpException
     */
    public function verifyRemote($imgUrl)
    {
        $imgUrl = str_replace("&amp;", "&", htmlspecialchars($imgUrl));
        // http开头验证
        if (strpos($imgUrl, "http") !== 0)
        {
            throw new NotFoundHttpException('不是一个http地址');
        }

        preg_match('/(^https?:\/\/[^:\/]+)/', $imgUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';

        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL))
        {
            throw new NotFoundHttpException('Url不合法');
        }

        preg_match('/^https?:\/\/(.+)/', $host_with_protocol, $matches);
        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';

        // 此时提取出来的可能是 IP 也有可能是域名，先获取 IP
        $ip = gethostbyname($host_without_protocol);

        // 获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK")))
        {
            throw new NotFoundHttpException('文件获取失败');
        }

        // Content-Type验证)
        if (!isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image"))
        {
            throw new NotFoundHttpException('格式验证失败');
        }

        $extend = StringHelper::clipping($imgUrl, '.', 1);
        if (!empty($this->config['extensions']) && !in_array($extend, $this->config['extensions']))
        {
            throw new NotFoundHttpException('文件类型不允许');
        }

        //打开输出缓冲区并获取远程图片
        ob_start();
        $context = stream_context_create(
            [
                'http' => [
                    'follow_location' => false // don't follow redirects
                ]
            ]
        );
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $size = strlen($img);
        if ($size > $this->config['maxSize'])
        {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        $this->fileBaseInfo = [
            'extension' => $extend,
            'size' => $size,
            'name' => $m ? $m[1] : "",
        ];

        $this->config['image'] = $img;

        return true;
    }

    /**
     * 切片
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function cut()
    {
        $config = $this->config;
        $chunk = $config['chunk'];
        $guid = $config['guid'];

        $chunk += 1;
        $fullPathName = $this->paths['tmpAbsolutePath'] . $chunk . '.' . $this->fileBaseInfo['extension'];
        $fileName = $guid . '.' . $this->fileBaseInfo['extension'];

        $fullPathName = StringHelper::iconvForWindows($fullPathName);
        if ($this->uploadedFile->saveAs($fullPathName))
        {
            $url = $this->paths['relativePath'] . $fileName;
            $config['fullPath'] == true && $url = Yii::$app->request->hostInfo . $url;

            // 判断如果上传成功就去合并文件
            if ($config['chunks'] == $chunk)
            {
                // 缓存上传信息等待回调
                Yii::$app->cache->set(self::$prefixForMergeCache . $guid, [
                    'config' => $config,
                    'type' => $this->type,
                    'relativePath' => $url,
                    'ultimatelyFilePath' => $this->paths['absolutePath'] . $fileName,
                    'tmpAbsolutePath' => $this->paths['tmpAbsolutePath'],
                    'extension' => $this->fileBaseInfo['extension'],
                ], 3600);
            }

            return  [
                'url' => $url,
                'merge' => true,
                'guid' => $guid,
            ];
        }

        throw new NotFoundHttpException('文件移动失败');
    }

    /**
     * 切片合并
     *
     * @param $ultimatelyFilePath
     * @param $directoryPath
     * @param $name
     * @param $originalExc
     * @param int $reconnectionNum
     */
    public static function merge($ultimatelyFilePath, $directoryPath, $name, $originalExc, $reconnectionNum = 0)
    {
        $fileName = $name . '.' . $originalExc;
        $filePath = $directoryPath . $fileName;
        if (file_exists($filePath))
        {
            file_put_contents($ultimatelyFilePath, file_get_contents($filePath), FILE_APPEND);
            unlink($filePath);

            $name = $name + 1;
            self::merge($ultimatelyFilePath, $directoryPath, $name, $originalExc);
        }
        else
        {
            try
            {
                // 删除文件夹，如果删除失败重新去合并
                rmdir($directoryPath);
            }
            catch (\Exception $e)
            {
                // 重复三次去合并文件，合成失败不管了
                if ($reconnectionNum < 3)
                {
                    $reconnectionNum += 1;
                    self::merge($ultimatelyFilePath, $directoryPath, $name, $originalExc,$reconnectionNum);
                }
            }
        }
    }

    /**
     * 直接上传
     *
     * @return mixed|string
     * @throws NotFoundHttpException
     * @throws \OSS\Core\OssException
     * @throws \Exception
     */
    public function save($takeOverAction = '')
    {
        $takeOverAction = !empty($takeOverAction) ? $takeOverAction : $this->takeOverAction;

        $paths = $this->getPaths();
        $fileName = $this->fileName . '.' . $this->fileBaseInfo['extension'];
        $fileAbsolutePath = $paths['absolutePath'] . $fileName;

        // 切片上传
        if (isset($this->config['chunks']) && isset($this->config['guid']))
        {
            $takeOverAction = 'cut';
        }

        switch ($takeOverAction)
        {
            // 本地上传
            case 'local' :
                $url = $this->local($fileName, $fileAbsolutePath);

                // 授权图片才可执行
                if ($this->type == 'images')
                {
                    // 图片水印
                    $this->watermark($fileAbsolutePath);
                    // 图片压缩
                    $this->compress($fileAbsolutePath);
                    // 创建缩略图
                    $this->thumb($fileAbsolutePath);
                }

                return $this->getUrl($url);
                break;
            // 阿里oss上传
            case 'oss' :
                // 判断是否本地上传
                !empty($this->uploadedFile) && $fileAbsolutePath = $this->uploadedFile->tempName;
                return $this->oss($fileName, $fileAbsolutePath);
                break;
            // 七牛上传
            case 'qiniu' :
                // 判断是否本地上传
                !empty($this->uploadedFile) && $fileAbsolutePath = $this->uploadedFile->tempName;
                return $this->qiniu($fileName, $fileAbsolutePath);
                break;
            // base64上传
            case 'base64' :
                $url = $this->base64($fileName, $fileAbsolutePath);

                // 对象存储接管上传
                if ($this->takeOverAction != 'local')
                {
                    return $this->save();
                }

                return $this->getUrl($url);
                break;
            // 远程拉取
            case 'remote' :

                $url = $this->remote($fileName, $fileAbsolutePath);

                // 对象存储接管上传
                if ($this->takeOverAction != 'local')
                {
                    return $this->save();
                }

                return $this->getUrl($url);
                break;
            case 'cut' :
                return $this->cut();
                break;
        }

        throw new NotFoundHttpException('找不到上传方法');
    }

    /**
     * 获取生成路径信息
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function getPaths()
    {
        if (!empty($this->paths))
        {
            return $this->paths;
        }

        $config = $this->config;
        $this->fileName = $config['prefix'] . StringHelper::randomNum(time());
        // 保留原名称
        if ($config['originalName'] == true && !empty($this->fileBaseInfo['name']))
        {
            $this->fileName = $this->fileBaseInfo['name'];
        }

        // 文件路径
        $filePath = $config['path'] . date($config['subName'], time()) . "/";
        // 缩略图
        $thumbPath = Yii::$app->params['uploadConfig']['thumb']['path'] . date($config['subName'], time()) . "/";

        $paths = [
            'relativePath' => Yii::getAlias("@attachurl/") . $filePath, // 相对路径
            'absolutePath' => Yii::getAlias("@attachment/") . $filePath, // 绝对路径
            'thumbRelativePath' => Yii::getAlias("@attachurl/") . $thumbPath, // 缩略图相对路径
            'thumbAbsolutePath' => Yii::getAlias("@attachment/") . $thumbPath, // 缩略图绝对路径
        ];

        // 切片的临时路径
        if (isset($config['guid']))
        {
            $tmpPath = 'tmp/' . date($config['subName'], time()) . "/" . $config['guid'] . '/';
            $paths = ArrayHelper::merge($paths, [
                'tmpRelativePath' => Yii::getAlias("@attachurl/") . $tmpPath, // 临时相对路径
                'tmpAbsolutePath' => Yii::getAlias("@attachment/") . $tmpPath, // 临时绝对路径
            ]);
        }

        $this->paths = $paths;

        // 创建目录
        unset($paths['relativePath'], $paths['thumbRelativePath'], $paths['tmpRelativePath']);
        foreach ($paths as $key => $path)
        {
            if (!FileHelper::mkdirs($path))
            {
                throw new NotFoundHttpException('文件夹创建失败，请确认是否开启attachment文件夹写入权限');
            }
        }

        unset($paths);

        return $this->paths;
    }

    /**
     * 获取图片规则
     *
     * @param $url
     * @return string
     */
    public function getUrl($url)
    {
        if ($this->config['fullPath'] == true)
        {
            $url = Yii::$app->request->hostInfo . $url;
        }

        return $url;
    }

    /**
     * base64上传
     *
     * @param $fileName
     * @param $fileAbsolutePath
     * @throws NotFoundHttpException
     */
    public function base64($fileName, $fileAbsolutePath)
    {
        $data = base64_decode($this->config['image']);

        // 移动文件
        if (!(file_put_contents($fileAbsolutePath, $data) && file_exists($fileAbsolutePath)))
        {
            throw new NotFoundHttpException('上传失败');
        }

        return $this->paths['relativePath'] . $fileName;
    }

    /**
     * 远程拉取
     *
     * @param $fileName
     * @param $fileAbsolutePath
     * @return string
     * @throws NotFoundHttpException
     */
    public function remote($fileName, $fileAbsolutePath)
    {
        // 移动文件
        if (!(file_put_contents($fileAbsolutePath, $this->config['image']) && file_exists($fileAbsolutePath)))
        {
            throw new NotFoundHttpException('上传失败');
        }

        return $this->paths['relativePath'] . $fileName;
    }

    /**
     * @param $fileName
     * @param $fileAbsolutePath
     * @throws NotFoundHttpException
     */
    public function local($fileName, $fileAbsolutePath)
    {
        $fileAbsolutePath = StringHelper::iconvForWindows($fileAbsolutePath);
        if (!$this->uploadedFile->saveAs($fileAbsolutePath))
        {
            throw new NotFoundHttpException('文件上传失败');
        }

        return $this->paths['relativePath'] . $fileName;
    }

    /**
     * oss 上传
     *
     * @param $fileName
     * @param $fileAbsolutePath
     * @return mixed
     * @throws \OSS\Core\OssException
     */
    public function oss($fileName, $fileAbsolutePath)
    {
        $config = Yii::$app->debris->configAll();
        $accessKeyId = $config['storage_aliyun_accesskeyid'];
        $accessKeySecret = $config['storage_aliyun_accesskeysecret'];
        $endpoint = $config['storage_aliyun_endpoint'];
        $bucket = $config['storage_aliyun_bucket'];

        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $result = $ossClient->uploadFile($bucket, $fileName, $fileAbsolutePath);

        // 私有获取图片信息
        // $singUrl = $ossClient->signUrl($bucket, $name, 60*60*24);

        return $result['info']['url'];
    }

    /**
     * 七牛上传
     *
     * @param $fileName
     * @param $fileAbsolutePath
     * @return string
     * @throws \Exception
     */
    public function qiniu($fileName, $fileAbsolutePath)
    {
        $config = Yii::$app->debris->configAll();
        $ak = $config['storage_qiniu_accesskey'];
        $sk = $config['storage_qiniu_secrectkey'];
        $domain = $config['storage_qiniu_domain'];
        $bucket = $config['storage_qiniu_bucket'];

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        $qiniu->uploadFile($fileAbsolutePath, $fileName);
        $url = $qiniu->getLink($fileName);

        return 'http://' . $url;
    }

    /**
     * 水印
     *
     * @param $fullPathName
     * @return bool
     */
    protected function watermark($fullPathName)
    {
        if (Yii::$app->debris->config('sys_image_watermark_status') != true)
        {
            return true;
        }

        $local = Yii::$app->debris->config('sys_image_watermark_location');
        $watermarkImg = StringHelper::getLocalFilePath(Yii::$app->debris->config('sys_image_watermark_img'));
        if ($coordinate = DebrisHelper::getWatermarkLocation($fullPathName, $watermarkImg, $local))
        {
            // $aliasName = StringHelper::getAliasUrl($fullPathName, 'watermark');
            Image::watermark($fullPathName, $watermarkImg, $coordinate)
                ->save($fullPathName, ['quality' => 100]);
        }

        return true;
    }

    /**
     * 压缩
     *
     * @param $fullPathName
     * @return bool
     */
    protected function compress($fullPathName)
    {
        if ($this->config['compress'] != true)
        {
            return true;
        }

        $imgInfo = getimagesize($fullPathName);
        $compressibility = $this->config['compressibility'];

        $tmpMinSize = 0;
        foreach ($compressibility as $key => $item)
        {
            if ($this->fileBaseInfo['size'] >= $tmpMinSize && $this->fileBaseInfo['size'] < $key && $item < 100)
            {
                // $aliasName = StringHelper::getAliasUrl($fullPathName, 'compress');
                Image::thumbnail($fullPathName, $imgInfo[0] , $imgInfo[1])
                    ->save($fullPathName, ['quality' => $item]);

                break;
            }

            $tmpMinSize = $key;
        }

        return true;
    }

    /**
     * 缩略图
     *
     * @return bool
     */
    protected function thumb($absolutePath)
    {
        if (empty($this->config['thumb']))
        {
            return true;
        }

        $fileName = $this->fileName . '.' . $this->fileBaseInfo['extension'];

        foreach ($this->config['thumb'] as $value)
        {
            $thumbPath = $this->paths['thumbAbsolutePath'] . $fileName;
            $thumbPath = StringHelper::createThumbUrl($thumbPath, $value['widget'], $value['height']);

            // 裁剪从坐标0,60 裁剪一张300 x 20 的图片,并保存 不设置坐标则从坐标0，0开始
            // Image::crop($originalPath, $thumbWidget , $thumbHeight, [0, 60])->save($thumbOriginalPath), ['quality' => 100]);
            Image::thumbnail($absolutePath, $value['widget'], $value['height'])->save($thumbPath);
        }

        return true;
    }
}