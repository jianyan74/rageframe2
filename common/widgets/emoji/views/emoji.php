<?php

use common\helpers\Html;
use common\helpers\ArrayHelper;

?>

<?= Html::tag('span',
    '<i class="fa fa-smile-o" style="font-size: 16px;"></i>',
    ArrayHelper::merge([
        'class' => 'btn btn-white',
        'data-toggle' => 'modal',
        'data-target' => '#rfEmoji',
    ], $options)); ?>

<div class="modal fade" id="rfEmoji" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">×</a>
                    <h4>表情</h4>
                </div>
                <div class="modal-body emoji-body"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // 解析表情 qqWechatEmotionParser(text);
    var emojiBaseUrl = '<?= $baseUrl; ?>image/';
    var name = '<?= $name; ?>';
    var emojiTheme = '<?= $theme; ?>';
    var emojiMap = {};
    var emojiBindId = '<?= $bind_id; ?>';
    $(document).ready(function () {
        // 默认的解析方式
        if (emojiTheme === 'default') {
            emojiMap = defaultEmojiList;
            var tmpMap = [];


            $(emojiMap).each(function (i, data) {
                var emojiSpan = "<span class='rf-emoji' data-dismiss='modal' data-emoji='" + data.alt + "'><img class='p-xxs' src='" + emojiBaseUrl + data.url + ".gif'></span>";
                $('.emoji-body').append(emojiSpan);

                tmpMap[data.alt] = 'TMP' + data.url + '.gif';
            });

            console.log(tmpMap);

        } else {
            emojiMap = wechatEmojiList;
            emojiMap = emojiKeys(emojiMap);
            $(emojiMap).each(function (i, data) {
                var emojiSpan = "<span class='rf-emoji' data-dismiss='modal' data-emoji='" + data.key + "'><img class='p-xxs' src='" + data.value + "'></span>";
                $('.emoji-body').append(emojiSpan);
            });
        }

        // 加载完成事件
        $(document).trigger('emoji-ready-' + name);
    });

    $(document).on("click", ".emoji-body img", function () {
        var emoji = $(this).parent().data('emoji');
        if (emojiBindId) {
            $("#" + emojiBindId).insertAtCaret(emoji);
        }

        // 选择完成事件
        $(document).trigger('emoji-select-' + name, [emoji]);
    });

    (function ($) {
        $.fn.extend({
            insertAtCaret: function (myValue) {
                var $t = $(this)[0];
                if (document.selection) {
                    this.focus();
                    sel = document.selection.createRange();
                    sel.text = myValue;
                    this.focus();
                } else if ($t.selectionStart || $t.selectionStart == '0') {
                    var startPos = $t.selectionStart;
                    var endPos = $t.selectionEnd;
                    var scrollTop = $t.scrollTop;
                    $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                    this.focus();
                    $t.selectionStart = startPos + myValue.length;
                    $t.selectionEnd = startPos + myValue.length;
                    $t.scrollTop = scrollTop;
                } else {
                    this.value += myValue;
                    this.focus();
                }
            }
        });
    })(jQuery);

    function emojiKeys(map) {
        var list = [];
        for (var k in map) {
            list.push({
                'key': k,
                'value': map[k],
            })
        }

        return list;
    }
</script>