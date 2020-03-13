<?php

namespace common\components\uploaddrive;

use common\helpers\RegularHelper;
use common\models\common\Attachment;
use League\Flysystem\Filesystem;
use Overtrue\Flysystem\Cos\CosAdapter;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use Xxtime\Flysystem\Aliyun\OssAdapter;

/**
 * Interface DriveInterface
 * @package common\components\uploaddrive
 */
abstract class DriveInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var CosAdapter|OssAdapter|QiniuAdapter
     */
    protected $adapter;

    /**
     * 上传组件
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * DriveInterface constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->create();
    }

    /**
     * @return Filesystem
     */
    public function entity(): Filesystem
    {
        if (!$this->filesystem instanceof Filesystem) {
            $this->filesystem = new Filesystem($this->adapter);
        }

        return $this->filesystem;
    }

    /**
     * @param $baseInfo
     * @param $drive
     * @param $fullPath
     * @return mixed
     */
    public function getUrl($baseInfo, $drive, $fullPath)
    {
        $baseInfo = $this->baseUrl($baseInfo, $fullPath);

        if ($drive != Attachment::DRIVE_LOCAL && !RegularHelper::verify('url', $baseInfo['url'])) {
            $baseInfo['url'] = 'http://' . $baseInfo['url'];
        }

        return $baseInfo;
    }

    /**
     * 返回路由
     *
     * @param $baseInfo
     * @param $fullPath
     * @return $baseInfo
     */
    abstract protected function baseUrl($baseInfo, $fullPath);

    /**
     * @return mixed
     */
    abstract protected function create();
}