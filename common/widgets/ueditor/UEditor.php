<?php
namespace common\widgets\ueditor;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\widgets\ueditor\assets\AppAsset;

/**
 * 百度编辑器上传
 *
 * Class UEditor
 * @package common\widgets\webuploader
 */
class UEditor extends \yii\widgets\InputWidget
{
    /**
     * ueditor参数配置
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
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        // 注册资源文件
        $asset = AppAsset::register($this->getView());
        $this->value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        //常用配置项
        $config = [
            'serverUrl' => Url::to(['/ueditor/index']),
            'UEDITOR_HOME_URL' => $asset->baseUrl . '/',
            'lang' => 'zh-cn',
            'initialFrameHeight' => 400,
            'initialFrameWidth' => '100%',
            'enableAutoSave' => false,
            'toolbars' => [
                [
                    'fullscreen', 'source', 'undo', 'redo', '|',
                    'customstyle', 'paragraph', 'fontfamily', 'fontsize'
                ],
                [
                    'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat',
                    'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                    'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', '|',
                    'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                    'directionalityltr', 'directionalityrtl', 'indent', '|'
                ],
                [
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'unlink', '|','simpleupload',
                    'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'insertcode', 'pagebreak', '|',
                    'horizontal', 'inserttable', '|',
                    'print', 'preview', 'searchreplace', 'help'
                ]
            ],
        ];

        $this->config = ArrayHelper::merge($config, $this->config);
    }

    /**
     * @return string
     */
    public function run()
    {
        $id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        $config = Json::encode($this->config);

        //ready部分代码，是为了缩略图管理。UEditor本身就很大，在后台直接加载大文件图片会很卡。
        $script = <<<UEDITOR
        UE.getEditor('{$id}',{$config}).ready(function(){
            this.addListener( "beforeInsertImage", function ( type, imgObjs ) {
                for(var i=0;i < imgObjs.length;i++){
                    imgObjs[i].src = imgObjs[i].src.replace(".thumbnail","");
                }
            });
    });
UEDITOR;

        $this->getView()->registerJs($script);

        if ($this->hasModel())
        {
            return Html::activeTextarea($this->model, $this->attribute);
        }

        return Html::textarea(ArrayHelper::getValue($this->config, 'textarea', $this->name), $this->value, ['id' => $id]);
    }
}