<?php

namespace common\helpers;

use Yii;
use common\widgets\markdown\Markdown2HtmlAsset;

/**
 * Class MarkdownHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class MarkdownHelper
{
    /**
     * @param $content
     * @return string
     */
    public static function toHtml($content, $toc_id = '')
    {
        $js = <<<JS
    $(function() {
	    var markdownView = editormd.markdownToHTML('markdown-view', {
            // htmlDecode : true,  // Enable / disable HTML tag encode.
            // htmlDecode : "style,script,iframe",  // Note: If enabled, you should filter some dangerous HTML tags for website security.
            tocContainer: "#{$toc_id}",
            tocDropdown: true,
            tocTitle: "目录",
            taskList: true,
            flowChart: true,// 流程图
            sequenceDiagram: true,// 序列图
            tex: true,// 科学公式
	    });
    });
JS;

        Markdown2HtmlAsset::register(Yii::$app->view);
        Yii::$app->view->registerJs($js);

        return Html::tag('div', Html::textarea('', $content, [
            'style' => 'display:none;',
        ]), [
            'id' => 'markdown-view',
        ]);
    }
}