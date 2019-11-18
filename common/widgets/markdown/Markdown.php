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
     * 上传路径 + 驱动
     *
     * @var
     */
    public $server;

    /**
     * @var string
     */
    public $id = 'markdown-editor';

    /**
     * @throws yii\base\InvalidConfigException
     */
    public function init()
    {
        if (!$this->server) {
            $this->server = Url::toRoute([
                '/file/images-markdown',
                'drive' => Yii::$app->params['uploadConfig']['images']['drive'],
            ]);
        }

        $this->options = ArrayHelper::merge([
            'width' => "100%",
            'height' => 500,
            'emoji' => false,
            'taskList' => true,
            'flowChart' => true, // 流程图
            'sequenceDiagram' => true, // 序列图
            'tex' => true, // 科学公式
            'imageUpload' => true,
            'imageUploadURL' => $this->server,
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
        $baseUrl = Yii::$app->view->assetBundles[MarkdownAsset::class]->baseUrl;
        $this->options['path'] = $baseUrl . '/lib/';
        $options = Json::htmlEncode($this->options);

        $js = <<<JS
    $(function() {
        var options = $options;
 
        options['toolbarIcons'] = function() {
            // Or return editormd.toolbarModes[name]; // full, simple, mini
            // Using "||" set icons align right.
            return [
                "bold", "del", "italic", "quote", "|", 
                "h1", "h2", "h3", "h4", "h5", "h6", "|", 
                "list-ul", "list-ol", "hr", "|",
                "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "datetime", "html-entities", "pagebreak", "|",
                "ucwords", "uppercase", "lowercase", "|", 
                "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                "help", "info", 'customIcon', "undo", "redo"
            ]
        };

        // customIcon 自定义草稿箱加载
        
       // 自定义方法的图标 指定一个FontAawsome的图标类
      options['toolbarIconsClass'] = {
            customIcon: "fa-paste"
        };
      // 没有图标可以插入内容，字符串或HTML标签
      options['toolbarIconTexts'] = {
            customIcon: "从草稿箱加载"
        };
      // 图标的title
      options['lang']  = {
            toolbar: {
                customIcon: "从草稿箱加载"
            }
        };
      // 自定义工具栏按钮的事件处理
      options['toolbarHandlers'] = {
            customIcon: function(){
                // 读取缓存内容
                editor.CodeAutoSaveGetCache();
            }
        };
      // 自定义工具栏按钮的事件处理
      options['onload'] = function() {
            // 引入插件 执行监听方法
            editormd.loadPlugin("$baseUrl/plugins/code-auto-save/code-auto-save", function() {
                // 初始化插件 实现监听
                editor.CodeAutoSave();
            });
            
            // 微信/qq里面复制文件是可以的，在桌面直接复制文件这种方法浏览器不支持
            editormd.loadPlugin("$baseUrl/plugins/image-handle-paste/image-handle-paste", function() {
                // 初始化插件 实现监听
                editor.imagePaste();
            });
        };
        
        var editor = editormd("{$this->id}", options);

        // 清理缓存
        $(this).on("beforeSubmit", function (event) {
             // 删除缓存 
             editor.CodeAutoSaveDelCache(); 
             // 清空缓存的文档内容 
             editor.CodeAutoSaveEmptyCacheContent(); 
             // 自定义设置缓存 
             // editor.CodeAutoSaveSetCache('缓存内容');
        });
        
        // 模板插入
        $('.editorTemplate').click(function () {
            var content = $(this).data('content');
            content = content.toString();
            if (content.length === 0) {
                return;
            }
            
            editor.insertValue(content);
            editor.focus();
        });
        
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