<?php
namespace common\widgets\webuploader;

use common\components\UploadDrive;
use common\models\common\Attachment;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;
use yii\base\InvalidConfigException;
use common\helpers\StringHelper;
use common\widgets\webuploader\assets\AppAsset;

/**
 * 图片上传
 *
 * Class Images
 * @package common\widgets\webuploader
 * @author jianyan74 <751393839@qq.com>
 */
class Files extends InputWidget
{
    /**
     * webuploader参数配置
     *
     * @var array
     */
    public $config = [];

    /**
     * @var string
     */
    public $type = 'images';

    /**
     * 默认主题
     *
     * @var string
     */
    public $theme = 'default';

    /**
     * 默认主题配置
     *
     * @var array
     */
    public $themeConfig = [];

    /**
     * 盒子ID
     *
     * @var
     */
    protected $boxId;

    /**
     * @var
     */
    protected $typeConfig;

    /**
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $uploadUrl = [
            'images' => Url::to(['/file/images']),
            'videos' => Url::to(['/file/videos']),
            'voices' => Url::to(['/file/voices']),
            'files' => Url::to(['/file/files']),
        ];

        // 默认配置信息
        $this->typeConfig = Yii::$app->params['uploadConfig'][$this->type];

        $this->boxId = md5($this->name) . StringHelper::uuid('uniqid');
        $this->themeConfig = ArrayHelper::merge([
            'select' => true, // 显示选择文件
            'sortable' => true, // 是否开启排序
        ], $this->themeConfig);

        $this->config = ArrayHelper::merge([
            'compress' => false, // 压缩
            'auto' => false, // 自动上传
            'formData' => [
                'guid' => null,
                'md5' => null,
                'drive' => $this->typeConfig['drive'], // 默认本地 可修改 qiniu/oss/cos 上传
            ], // 表单参数
            'pick' => [
                'id' => '.upload-album-' . $this->boxId,
                'innerHTML' => '',// 指定按钮文字。不指定时优先从指定的容器中看是否自带文字。
                'multiple' => false, // 是否开起同时选择多个文件能力
            ],
            'accept' => [
                'title' => 'Images',// 文字描述
                'extensions' => implode(',', $this->typeConfig['extensions']), // 后缀
                'mimeTypes' => $this->typeConfig['mimeTypes'],// 上传文件类型
            ],
            'swf' => null, //
            'chunked' => false,// 开启分片上传
            'chunkSize' => 10 * 1024 * 1024,// 分片大小
            'server' => $uploadUrl[$this->type], // 默认上传地址
            'fileVal' => 'file', // 设置文件上传域的name
            'disableGlobalDnd' => true, // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
            'fileNumLimit' => 20, // 验证文件总数量, 超出则不允许加入队列
            'fileSizeLimit' => null, // 验证文件总大小是否超出限制, 超出则不允许加入队列 KB
            'fileSingleSizeLimit' => $this->typeConfig['maxSize'], // 验证单个文件大小是否超出限制, 超出则不允许加入队列 KB
            'prepareNextFile' => true,
            'duplicate' => true,

            /**-------------- 自定义的参数 ----------------**/
            'independentUrl' => false, // 独立上传地址,不受全局的地址上传影响
            'mergeUrl' => Url::to(['/file/merge']),
            'getOssPathUrl' => Url::to(['/file/get-oss-path']),
            'verifyMd5Url' => Url::to(['/file/verify-md5']),
            'callback' => null, // 上传成功回调js方法
            'callbackProgress' => null, // 上传进度回调
            'name' => $this->name,
            'boxId' => $this->boxId,
            'type' => $this->type,
        ], $this->config);

        if (!empty($this->typeConfig['takeOverUrl']) && $this->config['independentUrl'] == false) {
            $this->config['server'] = $this->typeConfig['takeOverUrl'];
        }
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        empty($value) && $value = [];
        if ($this->config['pick']['multiple'] == true ) {
            // 赋予默认值
            $name = $name . '[]';

            if ($value && !is_array($value)) {
                $value = json_decode($value, true);
                empty($value) && $value = unserialize($value);
                empty($value) && $value = [];
            }
        }

        if (!is_array($value)) {
            $tmp = $value;
            $value = [];
            $value[] = $tmp;
        }

        //  由于百度上传不能传递数组，所以转码成为json
        !isset($this->config['formData']) && $this->config['formData'] = [];

        // 阿里云js直传
        if (Attachment::DRIVE_OSS_JS == $this->config['formData']['drive']) {
            $path = $this->typeConfig['path'] . date($this->typeConfig['subName'], time()) . "/";
            $oss = UploadDrive::getOssJsConfig(
                $this->config['fileSingleSizeLimit'],
                $path,
                60 * 60 * 5
            );

            $this->config['server'] = $oss['host'];
            $this->config['formData'] = ArrayHelper::merge($this->config['formData'] , $oss);
        }

        foreach ($this->config['formData'] as &$datum) {
            if (!empty($datum) && is_array($datum)) {
                $datum = Json::encode($datum);
            }
        }

        $this->registerClientScript();

        return $this->render($this->theme, [
            'name' => $name,
            'value' => $value,
            'type' => $this->type,
            'boxId' => $this->boxId,
            'config' => $this->config,
            'themeConfig' => $this->themeConfig,
        ]);
    }

    /**
     * 注册资源
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        AppAsset::register($view);
        $boxId = $this->boxId;
        $jsConfig = Json::encode($this->config);
        $disabled = $this->themeConfig['sortable'] ?? true;

        $view->registerJs(<<<Js
    var sortable = '{$disabled}';
    if (sortable) {
           // 拖动排序
        Sortable.create(document.getElementById('{$boxId}'),{
            distance : 30,
            filter : ".upload-box"
        }); 
    }
        
    $(".upload-album-{$boxId}").InitMultiUploader({$jsConfig});
    // 兼容老IE
    document.body.ondrop = function (event) {
        event = event || window.event;
        if (event.preventDefault) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.returnValue = false;
            event.cancelBubble = true;
        }
    };
Js
        );
    }
}