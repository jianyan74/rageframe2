<?php

namespace common\components\uploaddrive;

use Yii;
use common\helpers\RegularHelper;

/**
 * Class Local
 * @package common\components\uploaddrive
 * @author jianyan74 <751393839@qq.com>
 */
class Local extends DriveInterface
{
    /**
     * @param $baseInfo
     * @param $fullPath
     * @return mixed
     */
    protected function baseUrl($baseInfo, $fullPath)
    {
        $baseInfo['url'] = Yii::getAlias('@attachurl') . '/' . $baseInfo['url'];
        if ($fullPath == true && !RegularHelper::verify('url', $baseInfo['url'])) {
            $baseInfo['url'] = Yii::$app->request->hostInfo . $baseInfo['url'];
        }

        return $baseInfo;
    }

    /**
     * 初始化
     */
    protected function create()
    {
        // 判断是否追加
        if (isset($this->config['superaddition']) && $this->config['superaddition'] === true) {
            $this->adapter = new \League\Flysystem\Adapter\Local(Yii::getAlias('@attachment'), FILE_APPEND);
        } else {
            $this->adapter = new \League\Flysystem\Adapter\Local(Yii::getAlias('@attachment'));
        }
    }
}