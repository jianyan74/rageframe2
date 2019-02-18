<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\helpers\HtmlHelper;
use common\helpers\AuthHelper;
?>

<div class="row separateFromNextBlock">
    <div class="col-sm-7">
        <div class="btn-group">
            <?php foreach ($allMediaType as $key => $mo){ ?>
                <a class="btn <?= $mediaType == $key ? 'btn-primary': 'btn-white' ;?>" href="<?= Url::to(['index', 'type' => $key])?>"><?= $mo ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="col-sm-3">
        <?php if($mediaType != 'news'){ ?>
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['index']),
                'method' => 'get'
            ]); ?>
            <div class="input-group m-b">
                <?= Html::textInput('keywords', $keywords, [
                    'placeholder' => '请输入关键字',
                    'class' => 'form-control'
                ])?>
                <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
            </div>
            <?= Html::hiddenInput('type' , $mediaType)?>
            <?php ActiveForm::end(); ?>
        <?php } ?>
    </div>
    <div class="col-sm-2">
        <div class="pull-right" style="margin-top: 4px">
            共 <strong class="text-danger"><?= $count ?></strong> 条
            <?php if(AuthHelper::verify('/wechat/attachment/get-all-attachment')){ ?>
                <a class="btn btn-primary btn-xs" id="getAllAttachment">
                    <i class="fa fa-cloud-download"></i> 同步
                </a>
            <?php } ?>
            <?php if($mediaType == 'news'){ ?>
                <?php if(AuthHelper::verify('/wechat/attachment/news-create')){ ?>
                    <a id="createPostBtn" class="btn btn-primary btn-xs">
                        <i class="fa fa-plus"></i> 创建
                    </a>
                <?php } ?>
            <?php }else{ ?>
                <?= HtmlHelper::create([$mediaType . '-create'], '新增', [
                    'data-toggle' => 'modal',
                    'data-target' => '#ajaxModal',
                ])?>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    // 获取资源
    $("#getAllAttachment").click(function(){
        rfAffirm('同步中,请不要关闭当前页面');
        sync();
    });

    // 正式同步
    function sync(offset = 0,count = 20){
        $.ajax({
            type:"get",
            url:"<?= Url::to(['get-all-attachment','type' => $mediaType])?>",
            dataType: "json",
            data: {offset:offset,count:count},
            success: function(data){
                if (data.code == 200) {
                    var data = data.data;
                    sync(data.offset,data.count);
                } else if(data.code == 201) {
                    rfAffirm(data.message);
                    window.location.reload();
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>