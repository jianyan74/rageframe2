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
    protected static $config = [];

    /**
     * 七牛云存储上传
     *
     * @param $file
     * @return array
     * @throws \Exception
     */
    public static function qiniu($file)
    {
        $ak = Yii::$app->debris->config('storage_qiniu_accesskey');
        $sk = Yii::$app->debris->config('storage_qiniu_secrectkey');
        $domain = Yii::$app->debris->config('storage_qiniu_domain');
        $bucket = Yii::$app->debris->config('storage_qiniu_bucket');

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
        $accessKeyId = Yii::$app->debris->config('storage_aliyun_accesskeyid');
        $accessKeySecret = Yii::$app->debris->config('storage_aliyun_accesskeysecret');
        $endpoint = Yii::$app->debris->config('storage_aliyun_endpoint');
        $bucket = Yii::$app->debris->config('storage_aliyun_bucket');

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
     * @param $type
     * @param $base64Data
     * @return array
     * @throws NotFoundHttpException
     */
    public static function Base64Img($base64Data)
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
        $fileName = $config['prefix'] . StringHelper::random(10) . ".jpg";
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
    public static function upload($fileVal, $type)
    {
        $config = self::$config = Yii::$app->params['uploadConfig'][$type];

        $uploadFile = UploadedFile::getInstanceByName($fileVal);
        $info = self::getUploadFileInfo($uploadFile);

        if ($uploadFile->size > $config['maxSize'])
        {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        if (!empty($config['extensions']) && !in_array($uploadFile->getExtension(), $config['extensions']))
        {
            throw new NotFoundHttpException('文件类型不允许');
        }

        if (!FileHelper::mkdirs($info['absolutePath']))
        {
            throw new NotFoundHttpException('文件夹创建失败，请确认是否开启attachment文件夹写入权限');
        }

        // 返回数据
        $fullPathName = $info['absolutePath'] . $info['name'];
        if ($uploadFile->saveAs($fullPathName))
        {
            if ($config['fullPath'] == true)
            {
                $info['relativePath'] = Yii::$app->request->hostInfo . $info['relativePath'];
                $info['thumbRelativePath'] = Yii::$app->request->hostInfo . $info['thumbRelativePath'];
            }

            return $info;
        }

        throw new NotFoundHttpException('文件移动失败');
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
    public static function chunks($guid, $chunk, $chunks, $fileVal, $type)
    {
        $config = self::$config = Yii::$app->params['uploadConfig'][$type];
        $uploadFile = UploadedFile::getInstanceByName($fileVal);
        $info = self::getUploadFileInfo($uploadFile, $guid);

        if ($uploadFile->size > $config['maxSize'])
        {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        if (!empty($config['extensions']) && !in_array($uploadFile->getExtension(), $config['extensions']))
        {
            throw new NotFoundHttpException('文件类型不允许');
        }

        if (!FileHelper::mkdirs($info['tmpAbsolutePath']) || !FileHelper::mkdirs($info['absolutePath']))
        {
            throw new NotFoundHttpException('文件夹创建失败，请确认是否开启attachment文件夹写入权限');
        }

        $chunk += 1;
        $fullPathName = $info['tmpAbsolutePath'] . $chunk . '.' . $uploadFile->getExtension();

        $fileName = $guid . '.' . $uploadFile->getExtension();
        $ultimatelyFilePath = $info['absolutePath'] . $fileName;
        if ($uploadFile->saveAs($fullPathName))
        {
            if ($chunks == $chunk)
            {
                self::mergeFile($ultimatelyFilePath, $info['tmpAbsolutePath'], 1, $uploadFile->getExtension());
            }

            $urlPath = $info['relativePath'] . $fileName;
            if ($config['fullPath'] == true)
            {
                $urlPath = Yii::$app->request->hostInfo . $urlPath;
            }

            return  [
                'urlPath' => $urlPath,
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
    private static function getUploadFileInfo($file, $guid = null)
    {
        $config = self::$config;

        // 新名称
        $newBaseName = $config['prefix'] . StringHelper::randomNum(time());
        $config['originalName'] == true && $newBaseName = $file->getBaseName();
        $name = $newBaseName . '.' . $file->getExtension();

        // 文件路径
        $filePath = $config['path'] . date($config['subName'], time()) . "/";
        // 缩略图
        $thumbPath = Yii::$app->params['uploadConfig']['thumb']['path'] . date($config['subName'], time()) . "/";
        $tmpPath = 'tmp/' . date($config['subName'], time()) . "/" . $guid . '/';

        $info = [
            'name' => $name,
            'baseName' => $newBaseName,
            'relativePath' => Yii::getAlias("@attachurl/") . $filePath,
            'absolutePath' => Yii::getAlias("@attachment/") . $filePath,
            'thumbRelativePath' => Yii::getAlias("@attachurl/") . $thumbPath,
            'thumbAbsolutePath' => Yii::getAlias("@attachment/") . $thumbPath,
            'tmpRelativePath' => Yii::getAlias("@attachurl/") . $tmpPath,
            'tmpAbsolutePath' => Yii::getAlias("@attachment/") . $tmpPath,
        ];

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
        return Image::thumbnail($originalPath, $thumbWidget, $thumbHeight)->save($thumbOriginalPath);
    }

    /**
     * @param $ultimatelyFilePath
     * @param $directoryPath
     * @param $name
     * @param $originalExc
     */
    private static function mergeFile($ultimatelyFilePath, $directoryPath, $name, $originalExc,$reconnectionNum = 0)
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
                if ($reconnectionNum < 3)
                {
                    sleep(1);
                    $reconnectionNum += 1;
                    self::mergeFile($ultimatelyFilePath, $directoryPath, $name, $originalExc,$reconnectionNum);
                }
            }
        }
    }

    /**
     * 拉取远程图片
     */
    public static function saveRemote()
    {
//        $imgUrl = htmlspecialchars($this->fileField);
//        $imgUrl = str_replace("&amp;", "&", $imgUrl);
//
//        //http开头验证
//        if (strpos($imgUrl, "http") !== 0) {
//            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_LINK");
//            return;
//        }
//
//        preg_match('/(^https?:\/\/[^:\/]+)/', $imgUrl, $matches);
//        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';
//
//        // 判断是否是合法 url
//        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
//            $this->stateInfo = $this->getStateInfo("INVALID_URL");
//            return;
//        }
//
//        preg_match('/^https?:\/\/(.+)/', $host_with_protocol, $matches);
//        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';
//
//        // 此时提取出来的可能是 IP 也有可能是域名，先获取 IP
//        $ip = gethostbyname($host_without_protocol);
//
//        // 判断是否允许私有 IP
//        if (!$this->allowIntranet && !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
//            $this->stateInfo = $this->getStateInfo("INVALID_IP");
//            return;
//        }
//
//        //获取请求头并检测死链
//        $heads = get_headers($imgUrl, 1);
//        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
//            $this->stateInfo = $this->getStateInfo("ERROR_DEAD_LINK");
//            return;
//        }
//        //格式验证(扩展名验证和Content-Type验证)
//        $fileType = strtolower(strrchr($imgUrl, '.'));
//        if (!in_array($fileType, $this->config['allowFiles']) || !isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
//            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_CONTENTTYPE");
//            return;
//        }
//
//        //打开输出缓冲区并获取远程图片
//        ob_start();
//        $context = stream_context_create(
//            [
//                'http' => [
//                    'follow_location' => false // don't follow redirects
//                ]
//            ]
//        );
//        readfile($imgUrl, false, $context);
//        $img = ob_get_contents();
//        ob_end_clean();
//        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);
//
//        $this->oriName = $m ? $m[1] : "";
//
//        $this->fileSize = strlen($img);
//        $this->fileType = $this->getFileExt();
//        $this->fullName = $this->getFullName();
//        $this->filePath = $this->getFilePath();
//        $this->fileName = $this->getFileName();
//        $dirname = dirname($this->filePath);
//
//        //检查文件大小是否超出限制
//        if (!$this->checkSize()) {
//            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
//            return;
//        }
//
//        //创建目录失败
//        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
//            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
//            return;
//        } else if (!is_writeable($dirname)) {
//            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
//            return;
//        }
//
//        //移动文件
//        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
//            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
//        } else { //移动成功
//            $this->stateInfo = $this->stateMap[0];
//        }
    }
}