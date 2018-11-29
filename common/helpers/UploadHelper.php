<?php
namespace common\helpers;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use crazyfd\qiniu\Qiniu;
use OSS\OssClient;

/**
 * 上传辅助类
 *
 * Class UploadHelper
 * @package common\helpers
 */
class UploadHelper
{
    /**
     * 配置信息
     *
     * @var array
     */
    public static $config = [];

    /**
     * 当前文件信息
     *
     * @var
     */
    public static $fileInfo;

    /**
     * 当前文件路径
     *
     * @var
     */
    public static $filePath = [];

    /**
     * 上传类型
     *
     * @var
     */
    public static $type;

    /**
     * 缓存前缀
     *
     * @var string
     */
    public static $prefixForMergeCache = 'upload-file-guid:';

    /**
     * 上传方法
     *
     * 默认为直接上传
     * upload || chunks
     * @var string
     */
    protected static $action = 'upload';

    /**
     * 载入初始化资源
     *
     * @param $config
     * @param $type
     * @param string $fileName
     */
    public static function load($config, $type, $fileName = 'file')
    {
        self::$type = $type;
        // 合并基础信息
        unset($config['id'], $config['name'], $config['type'], $config['lastModifiedDate'], $config['size']);
        self::$config = ArrayHelper::merge(Yii::$app->params['uploadConfig'][$type], $config);
        self::$fileInfo = UploadedFile::getInstanceByName($fileName);
        self::$filePath = self::getFilePath();

        // 解密json
        foreach (self::$config as &$item)
        {
            if (!empty($item) && !is_numeric($item) && !is_array($item))
            {
                !empty(json_decode($item)) && $item = json_decode($item, true);
            }
        }

        // 切片上传
        if (isset(self::$config['chunks']) && isset(self::$config['guid']))
        {
            self::$action = 'chunks';
        }
    }

    /**
     * 上传方法
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function file()
    {
        $config = self::$config;

        if (self::$fileInfo->size > $config['maxSize'])
        {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        if (!empty($config['extensions']) && !in_array(self::$fileInfo->getExtension(), $config['extensions']))
        {
            throw new NotFoundHttpException('文件类型不允许');
        }

        // 缩略图文件夹
        if (!empty($config['thumb']) && !FileHelper::mkdirs(self::$filePath['thumbAbsolutePath']))
        {
            throw new NotFoundHttpException('缩略图文件夹创建失败，请确认是否开启attachment文件夹写入权限');
        }

        // 切片临时文件夹
        if (self::$action == 'chunks' && !FileHelper::mkdirs(self::$filePath['tmpAbsolutePath']))
        {
            throw new NotFoundHttpException('临时文件夹创建失败，请确认是否开启attachment文件夹写入权限');
        }

        if (!FileHelper::mkdirs(self::$filePath['absolutePath']))
        {
            throw new NotFoundHttpException('文件夹创建失败，请确认是否开启attachment文件夹写入权限');
        }

        $action = self::$action;
        return self::$action();
    }

    /**
     * 七牛云存储上传
     *
     * @param $file
     * @return array
     * @throws \Exception
     */
    public static function qiniu($file)
    {
        $config = Yii::$app->debris->configAll();
        $ak = $config['storage_qiniu_accesskey'];
        $sk = $config['storage_qiniu_secrectkey'];
        $domain = $config['storage_qiniu_domain'];
        $bucket = $config['storage_qiniu_bucket'];

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        $key = 'rf_' . time() . StringHelper::randomNum();
        $qiniu->uploadFile($file['tmp_name'], $key);
        $url = $qiniu->getLink($key);

        return [
            'urlPath' => 'http://' . $url,
        ];
    }

    /**
     * 阿里云oss
     *
     * @param $file
     * @return array
     * @throws \OSS\Core\OssException
     */
    public static function oss($file)
    {
        $config = Yii::$app->debris->configAll();
        $accessKeyId = $config['storage_aliyun_accesskeyid'];
        $accessKeySecret = $config['storage_aliyun_accesskeysecret'];
        $endpoint = $config['storage_aliyun_endpoint'];
        $bucket = $config['storage_aliyun_bucket'];

        $originalName = $file['name'];// 原名称
        $originalExc = StringHelper::clipping($originalName);// 后缀
        $name = 'rf_' . time() . StringHelper::randomNum() . $originalExc;
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $result = $ossClient->uploadFile($bucket, $name, $file['tmp_name']);

        // 私有获取图片信息
        // $singUrl = $ossClient->signUrl($bucket, $name, 60*60*24);

        return [
            'urlPath' => $result['info']['url'],
        ];
    }

    /**
     * base64上传
     *
     * @param $base64Data
     * @param string $extend
     * @return array
     * @throws NotFoundHttpException
     */
    public static function Base64Img($base64Data, $extend = 'jpg')
    {
        $base64Data = base64_decode($base64Data);

        $config = Yii::$app->params['uploadConfig']['images'];
        $path = $config['path'] . date($config['subName'], time()) . "/";
        $absolutePath = Yii::getAlias('@attachment') . '/' . $path;

        if (!FileHelper::mkdirs($absolutePath))
        {
            throw new NotFoundHttpException('文件夹创建失败，请确认是否开启attachment文件夹写入权限');
        }
        // 保存的图片名
        $fileName = $config['prefix'] . StringHelper::random(10) . "." . $extend;
        $filePath = $absolutePath . $fileName;
        // 移动文件
        if (!(file_put_contents($filePath, $base64Data) && file_exists($filePath)))
        {
            throw new NotFoundHttpException('上传失败');
        }

        $urlPath = Yii::getAlias("@attachurl/") . $path  . $fileName;
        $config['fullPath'] == true && $urlPath = Yii::$app->request->hostInfo . $urlPath;

        return  [
            'urlPath' => $urlPath,
        ];
    }

    /**
     * 通用上传
     * 支持文件，图片，语音等格式等上传
     *
     * @param string $fileVal 设置文件上传域的name
     * @param $type
     * @return array
     * @throws NotFoundHttpException
     */
    private static function upload()
    {
        $config = self::$config;

        // 返回数据
        $fullPathName = self::$filePath['absolutePath'] . self::$filePath['name'];
        $fullPathName = StringHelper::iconvForWindows($fullPathName);
        if (!self::$fileInfo->saveAs($fullPathName))
        {
            throw new NotFoundHttpException('文件上传失败');
        }

        // 如果是图片
        if (self::$type == 'images' && self::$action != 'chunks')
        {
            $imgInfo = getimagesize($fullPathName);

            // 判断水印
            if (Yii::$app->debris->config('sys_image_watermark_status') == true)
            {
                $local = Yii::$app->debris->config('sys_image_watermark_location');

                $watermarkImg = StringHelper::getLocalFilePath(Yii::$app->debris->config('sys_image_watermark_img'));
                if ($coordinate = DebrisHelper::getWatermarkLocation($fullPathName, $watermarkImg, $local))
                {
                    // $aliasName = StringHelper::getAliasUrl($fullPathName, 'watermark');
                    Image::watermark($fullPathName, $watermarkImg, $coordinate)
                        ->save($fullPathName, ['quality' => 100]);
                }
            }

            // 判断压缩
            if ($config['compress'] == true)
            {
                $compressibility = $config['compressibility'];

                $tmpMinSize = 0;
                foreach ($compressibility as $key => $item)
                {
                    if (self::$fileInfo->size >= $tmpMinSize && self::$fileInfo->size < $key && $item < 100)
                    {
                        // $aliasName = StringHelper::getAliasUrl($fullPathName, 'compress');
                        Image::thumbnail($fullPathName, $imgInfo[0] , $imgInfo[1])
                            ->save($fullPathName, ['quality' => $item]);

                        break;
                    }

                    $tmpMinSize = $key;
                }
            }

            // 裁剪成为缩略图
            if (!empty($config['thumb']))
            {
                foreach ($config['thumb'] as $value)
                {
                    $thumbPath = self::$filePath['thumbAbsolutePath'] . self::$filePath['name'];
                    $thumbPath = StringHelper::createThumbUrl($thumbPath, $value['widget'], $value['height']);
                    UploadHelper::createThumb(self::$filePath['absolutePath'] . self::$filePath['name'], $thumbPath, $value['widget'], $value['height']);
                }
            }
        }

        if ($config['fullPath'] == true)
        {
            $info['urlPath'] = Yii::$app->request->hostInfo . self::$filePath['relativePath'] . self::$filePath['name'];
        }

        return $info;
    }

    /**
     * 切片上传
     *
     * @param $guid
     * @param $chunk
     * @param $chunks
     * @param $fileVal
     * @param $type
     * @return array
     * @throws NotFoundHttpException
     */
    private static function chunks()
    {
        $config = self::$config;
        $chunk = $config['chunk'];
        $guid = $config['guid'];

        $chunk += 1;
        $fullPathName = self::$filePath['tmpAbsolutePath'] . $chunk . '.' . self::$fileInfo->getExtension();
        $fileName = $guid . '.' . self::$fileInfo->getExtension();

        $fullPathName = StringHelper::iconvForWindows($fullPathName);
        if (self::$fileInfo->saveAs($fullPathName))
        {
            $urlPath = self::$filePath['relativePath'] . $fileName;
            $config['fullPath'] == true && $urlPath = Yii::$app->request->hostInfo . $urlPath;

            // 判断如果上传成功就去合并文件
            if ($config['chunks'] == $chunk)
            {
                // 缓存上传信息等待回调
                Yii::$app->cache->set(self::$prefixForMergeCache . $guid, [
                    'config' => $config,
                    'type' => self::$type,
                    'relativePath' => $urlPath,
                    'ultimatelyFilePath' => self::$filePath['absolutePath'] . $fileName,
                    'tmpAbsolutePath' => self::$filePath['tmpAbsolutePath'],
                    'extension' => self::$fileInfo->getExtension(),
                ], 3600);
            }

            return  [
                'urlPath' => $urlPath,
                'merge' => true,
                'guid' => $guid,
            ];
        }

        throw new NotFoundHttpException('文件移动失败');
    }

    /**
     * 获取上传的文件信息
     *
     * @param object $file
     * @param $type
     * @return array
     */
    private static function getFilePath()
    {
        $config = self::$config;

        // 新名称
        $newBaseName = $config['prefix'] . StringHelper::randomNum(time());
        $name = $newBaseName;
        if ($config['originalName'] == true && !empty(self::$fileInfo))
        {
            $newBaseName = self::$fileInfo->getBaseName();
            $name = $newBaseName . '.' . self::$fileInfo->getExtension();
        }

        // 文件路径
        $filePath = $config['path'] . date($config['subName'], time()) . "/";
        // 缩略图
        $thumbPath = Yii::$app->params['uploadConfig']['thumb']['path'] . date($config['subName'], time()) . "/";

        $info = [
            'name' => $name, // 文件全称
            'baseName' => $newBaseName, // 文件基础名称不包含后缀
            'relativePath' => Yii::getAlias("@attachurl/") . $filePath, // 相对路径
            'absolutePath' => Yii::getAlias("@attachment/") . $filePath, // 绝对路径
            'thumbRelativePath' => Yii::getAlias("@attachurl/") . $thumbPath, // 缩略图相对路径
            'thumbAbsolutePath' => Yii::getAlias("@attachment/") . $thumbPath, // 缩略图绝对路径
        ];

        // 切片的临时路径
        if (isset($config['guid']))
        {
            $tmpPath = 'tmp/' . date($config['subName'], time()) . "/" . $config['guid'] . '/';
            $info = ArrayHelper::merge($info, [
                'tmpRelativePath' => Yii::getAlias("@attachurl/") . $tmpPath, // 临时相对路径
                'tmpAbsolutePath' => Yii::getAlias("@attachment/") . $tmpPath, // 临时绝对路径
            ]);
        }

        return $info;
    }

    /**
     * 生成缩略图
     *
     * @param string $originalPath 原来文件相对路径
     * @param string $thumbOriginalPath 缩略图地址
     * @param int $thumbWidget 宽度
     * @param int $thumbHeight 高度
     * @return \Imagine\Image\ManipulatorInterface
     */
    public static function createThumb($originalPath, $thumbOriginalPath, $thumbWidget, $thumbHeight)
    {
        // 裁剪从坐标0,60 裁剪一张300 x 20 的图片,并保存 不设置坐标则从坐标0，0开始
        // Image::crop($originalPath, $thumbWidget , $thumbHeight, [0, 60])->save($thumbOriginalPath), ['quality' => 100]);

        // 缩略图
        return Image::thumbnail($originalPath, $thumbWidget, $thumbHeight)->save($thumbOriginalPath);
    }

    /**
     * 合并文件
     *
     * @param $ultimatelyFilePath
     * @param $directoryPath
     * @param $name
     * @param $originalExc
     */
    public static function mergeFile($ultimatelyFilePath, $directoryPath, $name, $originalExc, $reconnectionNum = 0)
    {
        $fileName = $name . '.' . $originalExc;
        $filePath = $directoryPath . $fileName;
        if (file_exists($filePath))
        {
            file_put_contents($ultimatelyFilePath, file_get_contents($filePath), FILE_APPEND);
            unlink($filePath);

            $name = $name + 1;
            self::mergeFile($ultimatelyFilePath, $directoryPath, $name, $originalExc);
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
                    self::mergeFile($ultimatelyFilePath, $directoryPath, $name, $originalExc,$reconnectionNum);
                }
            }
        }
    }

    /**
     * 拉取远程图片
     *
     * @param $imgUrl
     * @throws NotFoundHttpException
     */
    public static function saveRemote($imgUrl)
    {
        $config = self::$config;
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

        // 格式验证(扩展名验证和Content-Type验证)
        $fileType = StringHelper::clipping($imgUrl, '.', 1);
        if (!in_array($fileType, $config['extensions']) || !isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image"))
        {
            throw new NotFoundHttpException('格式验证失败');
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


        $filePath = self::getFilePath();
        if (strlen($img) > $config['maxSize'])
        {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        if (!FileHelper::mkdirs($filePath['absolutePath']))
        {
            throw new NotFoundHttpException('文件夹创建失败，请确认是否开启attachment文件夹写入权限');
        }

        // 原始名称
        $oriName = $m ? $m[1] : "";

        // 移动文件
        $fileName = $filePath['name'] . '.' . $fileType;
        $fileFullPath = $filePath['absolutePath'] . $fileName;
        if (!(file_put_contents($fileFullPath, $img) && file_exists($fileFullPath)))
        {
            throw new NotFoundHttpException('文件移动失败');
        }

        $relativePath = $filePath['relativePath'] . $fileName;
        return $config['fullPath'] == true ? Yii::$app->request->hostInfo . $relativePath : $relativePath;
    }
}