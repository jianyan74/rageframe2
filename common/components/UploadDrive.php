<?php

namespace common\components;

use Yii;
use common\components\uploaddrive\Cos;
use common\components\uploaddrive\Local;
use common\components\uploaddrive\OSS;
use common\components\uploaddrive\Qiniu;
use common\helpers\ArrayHelper;

/**
 * Class UploadDrive
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class UploadDrive
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * UploadDrive constructor.
     */
    public function __construct()
    {
        $this->config = Yii::$app->debris->backendConfigAll();
    }

    /**
     * @param array $config
     * @return Local
     */
    public function local($config = [])
    {
        return new Local(ArrayHelper::merge($this->config, $config));
    }

    /**
     * @param array $config
     * @return OSS
     */
    public function oss($config = [])
    {
        return new OSS(ArrayHelper::merge($this->config, $config));
    }

    /**
     * @param array $config
     * @return Cos
     */
    public function cos($config = [])
    {
        return new Cos(ArrayHelper::merge($this->config, $config));
    }

    /**
     * @param array $config
     * @return Qiniu
     */
    public function qiniu($config = [])
    {
        return new Qiniu(ArrayHelper::merge($this->config, $config));
    }
}