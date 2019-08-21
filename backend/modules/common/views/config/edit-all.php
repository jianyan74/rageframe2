<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\helpers\StringHelper;

$this->title = '网站设置';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-md-9">
        <div class="tabs-container">
            <div class="tabs-left">
                <ul class="nav nav-tabs">
                    <?php foreach ($cates as $k => $cate) { ?>
                        <li <?php if ($k == 0){ ?>class="active"<?php } ?>>
                            <a aria-expanded="false" href="#tab-<?= $cate['id'] ?>"
                               data-toggle="tab"> <?= $cate['title'] ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content ">
                    <?php foreach ($cates as $k => $cate) { ?>
                        <div id="tab-<?= $cate['id'] ?>" class="tab-pane <?php if ($k == 0) { ?>active<?php } ?>">
                            <div class="panel-body">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'form-tab-' . $cate['id']
                                ]); ?>
                                <?php foreach ($cate['-'] as $item) { ?>
                                    <h2 style="font-size: 18px;padding-top: 0;margin-top: 0">
                                        <i class="icon ion-android-apps"></i>
                                        <?= $item['title'] ?>
                                    </h2>
                                    <div class="col-sm-12" style="padding-left: 18px;">
                                        <?php foreach ($item['config'] as $row) { ?>
                                            <?= $this->render($row['type'], [
                                                'row' => $row,
                                                'option' => StringHelper::parseAttr($row['extra']),
                                            ]) ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <span type="submit" class="btn btn-primary"
                                              onclick="present(<?= $cate['id'] ?>)">保存</span>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3" id="explain">
        <div class="box">
            <div class="box-body">
                <h4>说明：</h4>
                <h6>单击标题名称获取配置标识</h6>
                <div class="hr-line-dashed"></div>
                <h5 class="tag-title"></h5>
                <?= Html::input('text', 'demo', '',
                    ['class' => 'form-control', 'id' => 'demo', 'readonly' => 'readonly']); ?>
                <div class="hr-line-dashed"></div>
                <div class="clearfix">当前显示 ： <span id="demo-title">无</span></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        if ($(this).width() < 769 || config.isMobile == true) {
            $("#explain").addClass('hide');
            return;
        }

        // 当前高度
        let menuYloc = $("#explain").offset().top;
        $(window).scroll(function () {
            let offsetTop = $(window).scrollTop() - 40 + "px";
            $("#explain").animate({top: offsetTop}, {duration: 600, queue: false});

            if ($(window).scrollTop() < 60) {
                $("#explain").animate({top: 0}, {duration: 600, queue: false});
            }
        });
    });

    $(window).resize(function () {
        if ($(this).width() < 769 || config.isMobile == true) {
            $("#explain").addClass('hide');
        } else {
            $("#explain").removeClass('hide');
        }
    });

    // 单击
    $('.demo').click(function () {
        $('#demo').val($(this).attr('for'));
        $('#demo-title').text($(this).text());
    });

    function present(obj) {
        // 获取表单内信息
        let values = $("#form-tab-" + obj).serializeObject();
        $.ajax({
            type: "post",
            url: "<?= Url::to(['update-info'])?>",
            dataType: "json",
            data: values,
            success: function (data) {
                if (data.code === 200) {
                    rfAffirm(data.message);
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }

    function createKey(num, id) {
        let letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let token = '';
        for (let i = 0; i < num; i++) {
            let j = parseInt(Math.random() * 61 + 1);
            token += letters[j];
        }
        $("#" + id).val(token);
    }


    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        var $radio = $('input[type=radio],input[type=checkbox]',this);
        $.each($radio,function(){
            if(!o.hasOwnProperty(this.name)){
                o[this.name] = '';
            }
        });
        return o;
    };
</script>