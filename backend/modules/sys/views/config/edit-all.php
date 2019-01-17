<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\helpers\StringHelper;

$this->title = '网站设置';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-md-9">
        <div class="tabs-container">
            <div class="tabs-left">
                <ul class="nav nav-tabs">
                    <?php foreach ($cates as $k => $cate){ ?>
                        <li <?php if ($k == 0){ ?>class="active"<?php } ?>>
                            <a aria-expanded="false" href="#tab-<?= $cate['id'] ?>" data-toggle="tab"> <?= $cate['title'] ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content ">
                    <?php foreach ($cates as $k => $cate){ ?>
                        <div id="tab-<?= $cate['id'] ?>" class="tab-pane <?php if ($k == 0){ ?>active<?php } ?>">
                            <div class="panel-body">
                                <?php $form = ActiveForm::begin(['id' => 'form-tab-' . $cate['id']]); ?>
                                <?php foreach ($cate['-'] as $item){ ?>
                                    <h2 style="font-size: 20px;padding-top: 0;margin-top: 0">
                                        <i class="fa fa-tag"></i>
                                        <?= $item['title']?>
                                    </h2>
                                    <div class="col-sm-12" style="padding-left: 26px;">
                                        <?php foreach ($item['config'] as $row){ ?>
                                            <?= $this->render($row['type'], [
                                                'row' => $row,
                                                'option' => StringHelper::parseAttr($row['extra']),
                                            ]) ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <span type="submit" class="btn btn-primary" onclick="present(<?= $cate['id'] ?>)">保存</span>
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
                <h5>说明：</h5>
                <h6>单击标题名称获取配置标识</h6>
                <div class="hr-line-dashed"></div>
                <h5 class="tag-title"></h5>
                <?= Html::input('text','demo','',['class' => 'form-control','id'=>'demo','readonly' => 'readonly']);?>
                <div class="hr-line-dashed"></div>
                <div class="clearfix">当前显示 ： <span id="demo-title">无</span></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        if ($(this).width() < 769) {
            return;
        }

        // 当前高度
        var menuYloc = $("#explain").offset().top;
        $(window).scroll(function () {
            var offsetTop = $(window).scrollTop() - 40 + "px";
            $("#explain").animate({ top: offsetTop }, { duration: 600, queue: false });

            if ($(window).scrollTop() < 60){
                $("#explain").animate({ top:0}, { duration: 600, queue: false });
            }
        });
    });

    // 单击
    $('.demo').click(function(){
        $('#demo').val($(this).attr('for'));
        $('#demo-title').text($(this).text());
    });

    function present(obj){
        // 获取表单内信息
        var values = $("#form-tab-"+obj).serialize();

        $.ajax({
            type:"post",
            url:"<?= Url::to(['update-info'])?>",
            dataType: "json",
            data: values,
            success: function(data){
                if(data.code == 200) {
                    rfAffirm(data.message);
                }else{
                    rfAffirm(data.message);
                }
            }
        });
    }

    function createKey(num,id){
        var letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var token = '';
        for(var i = 0; i < num; i++) {
            var j = parseInt(Math.random() * 61 + 1);
            token += letters[j];
        }
        $("#"+id).val(token);
    }
</script>