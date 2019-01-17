<?php
namespace common\widgets\webuploader;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;
use yii\base\InvalidConfigException;
use common\helpers\StringHelper;
use common\widgets\webuploader\assets\AppAsset;
use common\widgets\webuploader\assets\WebuploaderAsset;

/**
 * 图片上传小工具
 *
 * Class Images
 * @package common\widgets\webuploader
 */
class Images extends InputWidget
{
    /**
     * webuploader参数配置
     *
     * @var array
     */
    public $config = [];

    /**
     * 默认名称
     *
     * @var string
     */
    public $name;

    /**
     * @var string|array
     */
    public $value;

    /**
     * 盒子ID
     *
     * @var
     */
    protected $boxId;

    /**
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        parent::init();
        $this->boxId = md5($this->name) . StringHelper::uuid('uniqid');
        $this->config = ArrayHelper::merge([
            'compress' => false, // 压缩
            'auto' => true, // 自动上传
            'formData' => [
                'guid' => null,
                'drive' => Yii::$app->params['uploadConfig']['images']['drive'], // 默认本地 可修改 qiniu/oss 上传
            ], // 表单参数
            'pick' => [
                'id' => '.upload-album-' . $this->boxId,
                'innerHTML' => '',// 指定按钮文字。不指定时优先从指定的容器中看是否自带文字。
                'multiple' => false, // 是否开起同时选择多个文件能力
            ],
            'accept' => [
                'title' => 'Images',// 文字描述
                'extensions' => implode(',', Yii::$app->params['uploadConfig']['images']['extensions']), // 后缀
                'mimeTypes' => 'image/*',// 上传文件类型
            ],
            'swf' => null, //
            'chunked' => false,// 开启分片上传
            'chunkSize' => 10 * 1024 * 1024,// 分片大小
            'server' => Url::to(['/file/images']), // 默认上传地址
            'fileVal' => 'file', // 设置文件上传域的name
            'disableGlobalDnd' => true, // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
            'fileNumLimit' => 20, // 验证文件总数量, 超出则不允许加入队列
            'fileSizeLimit' => null, // 验证文件总大小是否超出限制, 超出则不允许加入队列 KB
            'fileSingleSizeLimit' => Yii::$app->params['uploadConfig']['images']['maxSize'], // 验证单个文件大小是否超出限制, 超出则不允许加入队列 KB

            /**-------------- 自定义的参数 ----------------**/
            'uploadType' => 'image',
            'independentUrl' => false, // 独立上传地址,不受全局的地址上传影响
            'callback' => null, // 上传成功回调js方法
            'select' => true, // 显示选择文件
            'name' => $this->name,
        ], $this->config);

        if (!empty(Yii::$app->params['uploadConfig']['images']['takeOverUrl']) && $this->config['independentUrl'] == false)
        {
            $this->config['server'] = Yii::$app->params['uploadConfig']['images']['takeOverUrl'];
        }
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run()
    {
        $this->registerClientScript();
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        if ($this->config['pick']['multiple'] == true )
        {
            // 赋予默认值
            empty($value) && $value = [];
            $name = $name . '[]';

            if ($value && !is_array($value))
            {
                $value = json_decode($value, true);
                empty($value) && $value = unserialize($value);
                empty($value) && $value = [];
            }
        }

        //  由于百度上传不能传递数组，所以转码成为json
        !isset($this->config['formData']) && $this->config['formData'] = [];
        foreach ($this->config['formData'] as $key => &$formDatum)
        {
            if (!empty($formDatum) && is_array($formDatum))
            {
                $formDatum = json_encode($formDatum);
            }
        }

        return $this->render('image', [
            'name' => $name,
            'value' => $value,
            'boxId' => $this->boxId,
            'config' => $this->config,
        ]);
    }

    /**
     * 注册资源
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        WebuploaderAsset::register($view);
        AppAsset::register($view);
    }
}