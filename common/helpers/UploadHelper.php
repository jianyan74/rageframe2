<?php

namespace common\helpers;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\models\common\Attachment;
use common\components\UploadDrive;

/**
 * 上传辅助类
 *
 * Class UploadHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class UploadHelper
{
    /**
     * 切片合并缓存前缀
     */
    const PREFIX_MERGE_CACHE = 'upload-file-guid:';

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
     * 默认取 $_FILE['file']
     *
     * @var string
     */
    public $uploadFileName = 'file';

    /**
     * 上传驱动
     *
     * @var
     */
    protected $drive = 'local';

    /**
     * 拿取需要的数据
     *
     * @var array
     */
    protected $filter = [
        'thumb',
        'drive',
        'chunks',
        'chunk',
        'guid',
        'image',
        'compress',
        'width',
        'height',
        'md5'
    ];

    /**
     * 上传文件基础信息
     *
     * @var array
     */
    protected $baseInfo = [
        'name' => '',
        'width' => '',
        'height' => '',
        'size' => 0,
        'extension' => 'jpg',
        'url' => '',
        'merge' => false,
        'guid' => '',
        'type' => 'image/jpeg',
    ];

    /**
     * 是否切片上传
     *
     * @var bool
     */
    protected $isCut = false;

    /**
     * @var UploadDrive
     */
    protected $uploadDrive;

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * UploadHelper constructor.
     * @param array $config
     * @param string $type 文件类型
     * @param bool $superaddition 追加写入
     * @throws \Exception
     */
    public function __construct(array $config, $type, $superaddition = false)
    {
        // 过滤数据
        $this->filter($config, $type);
        // 设置文件类型
        $this->type = $type;
        // 初始化上传地址
        $this->initPaths();
        // 判断是否切片上传
        if (isset($this->config['chunks']) && isset($this->config['guid'])) {
            $this->drive = 'local';
            $this->isCut = true;
        }

        $this->uploadDrive = new UploadDrive($this->drive, $superaddition);
        $this->filesystem = $this->uploadDrive->getEntity();
    }

    /**
     * 验证文件
     *
     * @throws NotFoundHttpException
     */
    public function verifyFile()
    {
        $file = UploadedFile::getInstanceByName($this->uploadFileName);
        if ($file->getHasError()) {
            throw new NotFoundHttpException('上传失败，请检查文件');
        }

        $this->baseInfo['extension'] = $file->getExtension();
        $this->baseInfo['size'] = $file->size;

        empty($this->baseInfo['name']) && $this->baseInfo['name'] = $file->getBaseName();
        $this->baseInfo['url'] = $this->paths['relativePath'] . $this->baseInfo['name'] . '.' . $file->getExtension();

        unset($file);
        $this->verify();
    }

    /**
     * 验证Url
     *
     * @param $url
     * @return bool
     * @throws NotFoundHttpException
     */
    public function verifyUrl($url)
    {
        $imgUrl = str_replace("&amp;", "&", htmlspecialchars($url));
        // http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            throw new NotFoundHttpException('不是一个http地址');
        }

        preg_match('/(^https?:\/\/[^:\/]+)/', $imgUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';

        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
            throw new NotFoundHttpException('Url不合法');
        }

        preg_match('/^https?:\/\/(.+)/', $host_with_protocol, $matches);
        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';

        // 此时提取出来的可能是 IP 也有可能是域名，先获取 IP
        $ip = gethostbyname($host_without_protocol);

        // 获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            throw new NotFoundHttpException('文件获取失败');
        }

        // Content-Type验证)
        if (!isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
            throw new NotFoundHttpException('格式验证失败');
        }

        $extend = StringHelper::clipping($imgUrl, '.', 1);

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

        // $name = $m ? $m[1] : "",
        $this->baseInfo['extension'] = $extend;
        $this->baseInfo['size'] = strlen($img);
        $this->baseInfo['url'] = $this->paths['relativePath'] . $this->baseInfo['name'] . '.' . $extend;

        $this->verify();

        return $img;
    }

    /**
     * 验证base64格式的内容
     *
     * @param $data
     * @param $extend
     * @throws NotFoundHttpException
     */
    public function verifyBase64($data, $extend)
    {
        $this->baseInfo['extension'] = $extend;
        $this->baseInfo['size'] = strlen($data);
        $this->baseInfo['url'] = $this->paths['relativePath'] . $this->baseInfo['name'] . '.' . $extend;

        $this->verify();

        unset($data, $extend);
    }

    /**
     * 验证文件大小及类型
     *
     * @throws NotFoundHttpException
     */
    protected function verify()
    {
        if ($this->baseInfo['size'] > $this->config['maxSize']) {
            throw new NotFoundHttpException('文件大小超出网站限制');
        }

        if (!empty($this->config['extensions']) && !in_array($this->baseInfo['extension'],
                $this->config['extensions'])) {
            throw new NotFoundHttpException('文件类型不允许');
        }

        // 存储本地进行安全校验
        if ($this->drive == Attachment::DRIVE_LOCAL) {
            if ($this->type == Attachment::UPLOAD_TYPE_FILES && in_array($this->baseInfo['extension'], $this->config['blacklist']))  {
                throw new NotFoundHttpException('上传的文件类型不允许');
            }
        }
    }

    /**
     * 写入
     *
     * @param bool $data
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function save($data = false)
    {
        // 拦截 如果是切片上传就接管
        if ($this->isCut == true) {
            $this->cut();
            return;
        }

        // 判断如果文件存在就重命名文件名
        if ($this->filesystem->has($this->baseInfo['url'])) {
            $this->baseInfo['name'] = $this->baseInfo['name'] . '_' . StringHelper::randomNum();
            $this->baseInfo['url'] = $this->paths['relativePath'] . $this->baseInfo['name'] . '.' . $this->baseInfo['extension'];
        }

        // 判断是否直接写入
        if (false === $data) {
            $file = UploadedFile::getInstanceByName($this->uploadFileName);

            if (!$file->getHasError()) {
                $stream = fopen($file->tempName, 'r+');
                $result = $this->filesystem->writeStream($this->baseInfo['url'], $stream);


                if (!$result) {
                    throw new NotFoundHttpException('文件写入失败');
                }

                if (is_resource($stream)) {
                    fclose($stream);
                }
            } else {
                throw new NotFoundHttpException('上传失败，可能文件太大了');
            }
        } else {
            $result = $this->filesystem->write($this->baseInfo['url'], $data);

            if (!$result) {
                throw new NotFoundHttpException('文件写入失败');
            }
        }

        // 本地的图片才可执行
        if ($this->type == 'images' && $this->drive == 'local') {
            // 图片水印
            $this->watermark();
            // 图片压缩
            $this->compress();
            // 创建缩略图
            $this->thumb();

            // 获取图片信息
            if (empty($this->baseInfo['width']) && empty($this->baseInfo['height']) && $this->filesystem->has($this->baseInfo['url'])) {
                $imgInfo = getimagesize(Yii::getAlias('@attachment') . '/' . $this->baseInfo['url']);
                $this->baseInfo['width'] = $imgInfo[0] ?? 0;
                $this->baseInfo['height'] = $imgInfo[1] ?? 0;
            }
        }

        return;
    }

    /**
     * 水印
     *
     * @param $fullPathName
     * @return bool
     */
    protected function watermark()
    {
        if (Yii::$app->debris->config('sys_image_watermark_status') != true) {
            return true;
        }

        // 原图路径
        $absolutePath = Yii::getAlias("@attachment/") . $this->baseInfo['url'];

        $local = Yii::$app->debris->config('sys_image_watermark_location');
        $watermarkImg = StringHelper::getLocalFilePath(Yii::$app->debris->config('sys_image_watermark_img'));

        if ($coordinate = DebrisHelper::getWatermarkLocation($absolutePath, $watermarkImg, $local)) {
            // $aliasName = StringHelper::getAliasUrl($fullPathName, 'watermark');
            Image::watermark($absolutePath, $watermarkImg, $coordinate)
                ->save($absolutePath, ['quality' => 100]);
        }

        return true;
    }

    /**
     * 压缩
     *
     * @param $fullPathName
     * @return bool
     */
    protected function compress()
    {
        if ($this->config['compress'] != true) {
            return true;
        }

        // 原图路径
        $absolutePath = Yii::getAlias("@attachment/") . $this->baseInfo['url'];
        $imgInfo = getimagesize($absolutePath);
        $compressibility = $this->config['compressibility'];
        $tmpMinSize = 0;
        foreach ($compressibility as $key => $item) {
            if ($this->baseInfo['size'] >= $tmpMinSize && $this->baseInfo['size'] < $key && $item < 100) {
                // $aliasName = StringHelper::getAliasUrl($fullPathName, 'compress');
                Image::thumbnail($absolutePath, $imgInfo[0], $imgInfo[1])
                    ->save($absolutePath, ['quality' => $item]);

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
    protected function thumb()
    {
        if (empty($this->config['thumb'])) {
            return true;
        }

        // 原图路径
        $absolutePath = Yii::getAlias("@attachment/") . $this->baseInfo['url'];

        // 缩略图路径
        $path = Yii::getAlias("@attachment/") . $this->paths['thumbRelativePath'];
        FileHelper::mkdirs($path);
        $thumbPath = $path . $this->baseInfo['name'] . '.' . $this->baseInfo['extension'];

        foreach ($this->config['thumb'] as $value) {
            $thumbFullPath = StringHelper::createThumbUrl($thumbPath, $value['width'], $value['height']);

            // 裁剪从坐标0,60 裁剪一张300 x 20 的图片,并保存 不设置坐标则从坐标0，0开始
            // Image::crop($originalPath, $thumbWidth , $thumbHeight, [0, 60])->save($thumbOriginalPath), ['quality' => 100]);
            Image::thumbnail($absolutePath, $value['width'], $value['height'])->save($thumbFullPath);
        }

        return true;
    }

    /**
     * 切片
     *
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function cut()
    {
        // 切片参数
        $chunk = $this->config['chunk'] + 1;
        $guid = $this->config['guid'];

        // 临时文件夹路径
        $url = $this->paths['tmpRelativePath'] . $chunk . '.' . $this->baseInfo['extension'];

        // 上传
        $file = UploadedFile::getInstanceByName($this->uploadFileName);
        if ($file->error === UPLOAD_ERR_OK) {
            $stream = fopen($file->tempName, 'r+');
            $result = $this->filesystem->writeStream($url, $stream);
            fclose($stream);

            // 判断如果上传成功就去合并文件
            $this->baseInfo['chunk'] = $chunk;
            if ($this->config['chunks'] == $chunk) {
                // 缓存上传信息等待回调
                Yii::$app->cache->set(self::PREFIX_MERGE_CACHE . $guid, [
                    'type' => $this->type,
                    'drive' => $this->drive,
                    'paths' => $this->paths,
                    'baseInfo' => $this->baseInfo,
                    'config' => $this->config,
                ], 3600);
            }

            $this->baseInfo['merge'] = true;
            $this->baseInfo['guid'] = $guid;
        }
    }

    /**
     * 切片合并
     *
     * @param int $name
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function merge($name = 1)
    {
        // 由于合并会附带上一次切片的信息，取消切片判断
        $this->isCut = false;

        $filePath = $this->paths['tmpRelativePath'] . $name . '.' . $this->baseInfo['extension'];
        if ($this->filesystem->has($filePath) && ($content = $this->filesystem->read($filePath))) {
            if ($this->filesystem->has($this->baseInfo['url'])) {
                $this->filesystem->update($this->baseInfo['url'], $content);
            } else {
                $this->filesystem->write($this->baseInfo['url'], $content);
            }

            unset($content);
            $this->filesystem->delete($filePath);
            $name += 1;
            self::merge($name);
        } else {
            // 删除文件夹，如果删除失败重新去合并
            $this->filesystem->deleteDir($this->paths['tmpRelativePath']);
        }
    }

    /**
     * 获取生成路径信息
     *
     * @return array
     */
    protected function initPaths()
    {
        if (!empty($this->paths)) {
            return $this->paths;
        }

        $config = $this->config;
        // 保留原名称
        $config['originalName'] == false && $this->baseInfo['name'] = $config['prefix'] . StringHelper::randomNum(time());

        // 文件路径
        $filePath = $config['path'] . date($config['subName'], time()) . "/";
        // 缩略图
        $thumbPath = Yii::$app->params['uploadConfig']['thumb']['path'] . date($config['subName'], time()) . "/";

        empty($config['guid']) && $config['guid'] = StringHelper::randomNum();
        $tmpPath = 'tmp/' . date($config['subName'], time()) . "/" . $config['guid'] . '/';
        $this->paths = [
            'relativePath' => $filePath, // 相对路径
            'thumbRelativePath' => $thumbPath, // 缩略图相对路径
            'tmpRelativePath' => $tmpPath, // 临时相对路径
        ];

        return $this->paths;
    }

    /**
     * 过滤数据
     *
     * @param $config
     */
    protected function filter($config, $type)
    {
        try {
            // 解密json
            foreach ($config as $key => &$item) {
                if (!empty($item) && !is_numeric($item) && !is_array($item)) {
                    !empty(json_decode($item)) && $item = Json::decode($item);
                }
            }

            $config = ArrayHelper::filter($config, $this->filter);
            $this->config = ArrayHelper::merge(Yii::$app->params['uploadConfig'][$type], $config);
            // 参数
            $this->baseInfo['width'] = $this->config['width'] ?? 0;
            $this->baseInfo['height'] = $this->config['height'] ?? 0;
        } catch (\Exception $e) {
            $this->config = Yii::$app->params['uploadConfig'][$type];
        }

        !empty($this->config['drive']) && $this->drive = $this->config['drive'];
    }

    /**
     * 写入目录
     *
     * @param array $paths
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * 写入基础信息
     *
     * @param array $baseInfo
     */
    public function setBaseInfo(array $baseInfo)
    {
        $this->baseInfo = $baseInfo;
    }

    /**
     * @param mixed $drive
     */
    public function setDrive($drive)
    {
        $this->drive = $drive;
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function getBaseInfo()
    {
        // 是否切片
        if ($this->isCut == true) {
            return $this->baseInfo;
        }

        // 处理上传的文件信息
        $this->baseInfo['type'] = $this->filesystem->getMimetype($this->baseInfo['url']);
        $this->baseInfo['size'] = $this->filesystem->getSize($this->baseInfo['url']);
        $path = $this->baseInfo['url'];
        // 获取上传路径
        $this->baseInfo = $this->uploadDrive->getUrl($this->baseInfo, $this->config['fullPath']);

        // 写入数据库
        $attachment_id = Yii::$app->services->attachment->create([
            'drive' => $this->drive,
            'upload_type' => $this->type,
            'specific_type' => $this->baseInfo['type'],
            'size' => $this->baseInfo['size'],
            'width' => $this->baseInfo['width'],
            'height' => $this->baseInfo['height'],
            'extension' => $this->baseInfo['extension'],
            'name' => $this->baseInfo['name'],
            'md5' => $this->config['md5'] ?? '',
            'base_url' => $this->baseInfo['url'],
            'path' => $path
        ]);

        $this->baseInfo['id'] = $attachment_id;
        $this->baseInfo['formatter_size'] = Yii::$app->formatter->asShortSize($this->baseInfo['size'], 2);
        $this->baseInfo['upload_type'] = self::formattingFileType($this->baseInfo['type'], $this->baseInfo['extension'], $this->type);

        return $this->baseInfo;
    }

    /**
     * @param $specific_type
     * @param $extension
     * @return string
     */
    public static function formattingFileType($specific_type, $extension, $upload_type)
    {
        if (preg_match("/^image/", $specific_type) && $extension != 'psd') {
            return Attachment::UPLOAD_TYPE_IMAGES;
        }

        return $upload_type;
    }
}