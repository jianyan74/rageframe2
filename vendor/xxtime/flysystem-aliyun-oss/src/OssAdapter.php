<?php

namespace Xxtime\Flysystem\Aliyun;


use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use League\Flysystem\Util;
use OSS\OssClient;
use Exception;

class OssAdapter extends AbstractAdapter
{

    /**
     * @var OssClient
     */
    private $oss;

    /**
     * @var AliYun bucket
     */
    private $bucket;

    /**
     * @var string
     */
    private $endpoint = 'oss-cn-shanghai.aliyuncs.com';

    /**
     * OssAdapter constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct($config = [])
    {
        $isCName = false;
        $securityToken = null;
        try {
            $this->bucket = $config['bucket'];
            empty($config['endpoint']) ? null : $this->endpoint = $config['endpoint'];
            empty($config['timeout']) ? $config['timeout'] = 3600 : null;
            empty($config['connectTimeout']) ? $config['connectTimeout'] = 10 : null;

            if (!empty($config['isCName'])) {
                $isCName = true;
            }
            if (!empty($config['securityToken'])) {
                $securityToken = $config['securityToken'];
            }
            $this->oss = new OssClient(
                $config['access_id'], $config['access_secret'], $this->endpoint, $isCName, $securityToken
            );
            $this->oss->setTimeout($config['timeout']);
            $this->oss->setConnectTimeout($config['connectTimeout']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
    {
        return $this->oss->putObject($this->bucket, $path, $contents);
    }

    /**
     * Write a new file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
        $result = $this->write($path, stream_get_contents($resource), $config);
        if (is_resource($resource)) {
            fclose($resource);
        }
        return $result;
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        return $this->oss->putObject($this->bucket, $path, $contents);
    }

    /**
     * Update a file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function updateStream($path, $resource, Config $config)
    {
        $result = $this->write($path, stream_get_contents($resource), $config);
        if (is_resource($resource)) {
            fclose($resource);
        }
        return $result;
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function rename($path, $newpath)
    {
        $this->oss->copyObject($this->bucket, $path, $this->bucket, $newpath);
        $this->oss->deleteObject($this->bucket, $path);
        return true;
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($path, $newpath)
    {
        $this->oss->copyObject($this->bucket, $path, $this->bucket, $newpath);
        return true;
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
        $this->oss->deleteObject($this->bucket, $path);
        return true;
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($dirname)
    {
        $lists = $this->listContents($dirname, true);
        if (!$lists) {
            return false;
        }
        $objectList = [];
        foreach ($lists as $value) {
            $objectList[] = $value['path'];
        }
        $this->oss->deleteObjects($this->bucket, $objectList);
        return true;
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     *
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
        $this->oss->createObjectDir($this->bucket, $dirname);
        return true;
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     *
     * Aliyun OSS ACL value: 'default', 'private', 'public-read', 'public-read-write'
     */
    public function setVisibility($path, $visibility)
    {
        $this->oss->putObjectAcl(
            $this->bucket,
            $path,
            ($visibility == 'public') ? 'public-read' : 'private'
        );
        return true;
    }


    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return array|bool|null
     */
    public function has($path)
    {
        return $this->oss->doesObjectExist($this->bucket, $path);
    }

    /**
     * Read a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function read($path)
    {
        return [
            'contents' => $this->oss->getObject($this->bucket, $path)
        ];
    }

    /**
     * Read a file as a stream.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function readStream($path)
    {
        $resource = 'http://' . $this->bucket . '.' . $this->endpoint . '/' . $path;
        return [
            'stream' => $resource = fopen($resource, 'r')
        ];
    }

    /**
     * List contents of a directory.
     *
     * @param string $directory
     * @param bool $recursive
     *
     * @return array
     */
    public function listContents($directory = '', $recursive = false)
    {
        $directory = rtrim($directory, '\\/');

        $result = [];
        $nextMarker = '';
        while (true) {
            // max-keys 用于限定此次返回object的最大数，如果不设定，默认为100，max-keys取值不能大于1000。
            // prefix   限定返回的object key必须以prefix作为前缀。注意使用prefix查询时，返回的key中仍会包含prefix。
            // delimiter是一个用于对Object名字进行分组的字符。所有名字包含指定的前缀且第一次出现delimiter字符之间的object作为一组元素
            // marker   用户设定结果从marker之后按字母排序的第一个开始返回。
            $options = [
                'max-keys'  => 1000,
                'prefix'    => $directory . '/',
                'delimiter' => '/',
                'marker'    => $nextMarker,
            ];
            $res = $this->oss->listObjects($this->bucket, $options);

            // 得到nextMarker，从上一次$res读到的最后一个文件的下一个文件开始继续获取文件列表
            $nextMarker = $res->getNextMarker();
            $prefixList = $res->getPrefixList(); // 目录列表
            $objectList = $res->getObjectList(); // 文件列表
            if ($prefixList) {
                foreach ($prefixList as $value) {
                    $result[] = [
                        'type' => 'dir',
                        'path' => $value->getPrefix()
                    ];
                    if ($recursive) {
                        $result = array_merge($result, $this->listContents($value->getPrefix(), $recursive));
                    }
                }
            }
            if ($objectList) {
                foreach ($objectList as $value) {
                    if (($value->getSize() === 0) && ($value->getKey() === $directory . '/')) {
                        continue;
                    }
                    $result[] = [
                        'type'      => 'file',
                        'path'      => $value->getKey(),
                        'timestamp' => strtotime($value->getLastModified()),
                        'size'      => $value->getSize()
                    ];
                }
            }
            if ($nextMarker === '') {
                break;
            }
        }

        return $result;
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMetadata($path)
    {
        return $this->oss->getObjectMeta($this->bucket, $path);
    }

    /**
     * Get the size of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getSize($path)
    {
        $response = $this->oss->getObjectMeta($this->bucket, $path);
        return [
            'size' => $response['content-length']
        ];
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMimetype($path)
    {
        $response = $this->oss->getObjectMeta($this->bucket, $path);
        return [
            'mimetype' => $response['content-type']
        ];
    }

    /**
     * Get the timestamp of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getTimestamp($path)
    {
        $response = $this->oss->getObjectMeta($this->bucket, $path);
        return [
            'timestamp' => $response['last-modified']
        ];
    }

    /**
     * Get the visibility of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getVisibility($path)
    {
        $response = $this->oss->getObjectAcl($this->bucket, $path);
        return [
            'visibility' => $response,
        ];
    }

}