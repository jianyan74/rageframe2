<?php

namespace common\widgets\markdown;

use yii;
use yii\helpers\Json;
use yii\helpers\Html;
use common\helpers\Url;
use common\helpers\ArrayHelper;

/**
 * Class Markdown
 * @package common\widgets\markdown
 * @author jianyan74 <751393839@qq.com>
 */
class Markdown extends yii\widgets\InputWidget
{
    /**
     * @var string
     */
    public $id = 'markdown-editor';

    /**
     * @throws yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->options = ArrayHelper::merge([
            'width' => "100%",
            'height' => 500,
            'emoji' => false,
            'taskList' => true,
            'flowChart' => true, // 流程图
            'sequenceDiagram' => true, // 序列图
            'tex' => true, // 科学公式
            'imageUpload' => true,
            'imageUploadURL' => Url::toRoute([
                '/file/images-markdown',
                'drive' => Yii::$app->params['uploadConfig']['images']['drive'],
            ]),
        ], $this->options);

        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        // 注册资源
        MarkdownAsset::register($this->view);
        $this->options['path'] = Yii::$app->view->assetBundles[MarkdownAsset::class]->baseUrl . '/lib/';
        $options = Json::htmlEncode($this->options);

        $js = <<<JS
    $(function() {
        var options = $options;
        options['toolbarIcons'] = function() {
            // Or return editormd.toolbarModes[name]; // full, simple, mini
            // Using "||" set icons align right.
            return [
                "undo", "redo", "|", 
                "bold", "del", "italic", "quote", "|", 
                "h1", "h2", "h3", "h4", "h5", "h6", "|", 
                "list-ul", "list-ol", "hr", "|",
                "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "datetime", "html-entities", "pagebreak", "|",
                "ucwords", "uppercase", "lowercase", "|", 
                "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                "help", "info"
            ]
        };
        
        var editor = editormd("{$this->id}", options);
        
        $('.editormd-preview-close-btn').attr('style', 'display: none;')
    });
JS;

        $this->view->registerJs($js);
        if ($this->hasModel()) {
            $html = Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            $html = Html::textarea($this->name, $this->value, $this->options);
        }

        return Html::tag('div', $html, [
            'id' => $this->id,
        ]);
    }
}