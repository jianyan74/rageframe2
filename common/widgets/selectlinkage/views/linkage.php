<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<style>
    .linkage-list {
        overflow: hidden;
    }

    .linkage-list .item {
        position: relative;
        float: left;
        border: 1px solid #cccccc;
        background: #fff;
        height: 345px;
        margin: 0 0.5%;
        overflow-y: auto;
    }

    .linkage-list .item ul {
        padding: 0;
        margin: 0;
    }

    .linkage-list .item li {
        padding: 10px;
        cursor: pointer;
        font-size: 12px;
    }

    .linkage-list .item li .category-name {
        display: inline-block;
        margin-left: 4px;
        white-space: nowrap;
        width: 210px;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .linkage-list .item li:hover,
    .linkage-list .item li.selected {
        color: #ff8143 !important;
    }

    .linkage-list .item li .right-arrow {
        float: right;
    }
</style>

<div class="row" id="<?= $boxId; ?>">
    <div class="col-sm-12">
        <div class="input-group m-b">
            <?= Html::textInput('', $text, [
                'class' => 'form-control linkage-name',
                'disabled' => true,
            ]) ?>
            <?= Html::hiddenInput($name, $value, [
                'class' => 'form-control linkage-id',
            ]) ?>
            <span class="input-group-btn select-linkage-<?= $boxId; ?>"
                  data-toggle="modal"
                  data-target="#ajaxModalLg-<?= $boxId; ?>">
                <span class="btn btn-white"> 选择</span>
            </span>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxModalLg-<?= $boxId; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"><span aria-hidden="true">×</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">请选择</h4>
            </div>
            <div class="modal-body">
                <div class="linkage-list modal-<?= $boxId; ?>">
                    <?php for ($i = 1; $i < $level + 1; $i++) { ?>
                        <div class="col-lg-<?= $col ?> item" style="width: <?= $width ?>%">
                            <ul class="level-<?= $i ?>"></ul>
                        </div>
                    <?php } ?>
                </div>
                <label class="m-t-sm">您当前选择的是：<span class="selected-name"><?= $text;?></span></label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button class="btn btn-primary linkageConfirm-<?= $boxId; ?>" data-id="<?= $boxId; ?>" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>

<script>
    var boxId = "<?= $boxId;?>";
    var linkageUrl = "<?= $url;?>";
    var linkageData = [];
    var linkageItem = <?= $item; ?>;
    var linkageAlltItem = <?= $allItem ?>;
    var linkageDefaultItem = <?= $defaultItem; ?>;
    var linkageParents = <?= $parents; ?>;

    // 初始化渲染
    $(document).ready(function () {
        var linkageParentsLength = linkageParents.length;
        // 初始化选择器
        if (linkageParentsLength > 0) {
            for (let i = 1; i < linkageParentsLength + 1; i++) {
                linkageData[i] = {
                    'id': linkageParents[i-1]['id'],
                    'name': linkageParents[i-1]['title'],
                }

                // 判断键值存在
                if (linkageDefaultItem.hasOwnProperty(i)) {
                    for (let j = 0; j < linkageDefaultItem[i].length; j++) {
                        var linkageSelected = '';
                        if (linkageParents[i-1]['id'] == linkageDefaultItem[i][j]['id']) {
                            linkageSelected = 'selected';
                        }

                        var childText = '<li class="'+linkageSelected+'" data-id="' + linkageDefaultItem[i][j]['id'] + '" data-level="'+i+'"><span class="name">' + linkageDefaultItem[i][j]['title'] + '</span> <span class="right-arrow">&gt;</span> </li>'
                        $(".level-" + i).append(childText);
                    }
                }
            }
        } else {
            for (let i = 0; i < linkageAlltItem.length; i++) {
                var childText = '<li data-id="' + linkageAlltItem[i]['id'] + '" data-level="1"><span class="name">' + linkageAlltItem[i]['title'] + '</span> <span class="right-arrow">&gt;</span> </li>'
                $(".level-1").append(childText);
            }
        }
    });

    // 单击触发
    $(document).on("click", ".modal-" + boxId + " .item li", function () {
        $(this).parent().find('li').removeClass('selected')
        $(this).addClass('selected');
        var id = $(this).data('id');
        var name = $(this).find('.name').text();
        var level = $(this).data('level');
        linkageData[level] = {
            'id': id,
            'name': name,
        }

        for (let j = 1; j < 10; j++) {
            if (linkageData.hasOwnProperty(j)) {
                if (j > level) {
                    linkageData.splice($.inArray(j, linkageData), 1);
                }
            }

            if (j > level) {
                $('.level-' + j).html('')
            }
        }

        var text = '';
        var prefix = '';
        for (let i = 1; i < 10; i++) {
            if (linkageData.hasOwnProperty(i)) {
                if (text) {
                    prefix = '/';
                }

                text += prefix + linkageData[i]['name'];
            }
        }

        $('.selected-name').text(text);

        var childLevel = level + 1;
        $.ajax({
            type: "get",
            url: "<?= $url; ?>",
            dataType: "json",
            data: {pid: id},
            success: function (data) {
                if (data.code == 200) {
                    var list = data.data;
                    for (let n = 0; n < list.length; n++) {
                        console.log(list[n]);
                        var childText = '<li data-id="' + list[n]['id'] + '" data-level="' + childLevel + '"><span class="name">' + list[n]['title'] + '</span> <span class="right-arrow">&gt;</span> </li>'
                        $(".level-" + childLevel).append(childText);
                    }
                }
            }
        });
    })

    $(document).on("click", ".linkageConfirm-" + boxId, function () {
        var length = linkageData.length - 1;
        var id = $(this).data('id');
        $("#" + id).find('.linkage-id').val(linkageData[length]['id'])
        $("#" + id).find('.linkage-name').val($('.selected-name').text())
    });
</script>