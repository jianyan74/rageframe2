<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\helpers\HtmlHelper;
?>

<?= HtmlHelper::cssFile('@web/resources/plugins/cropper/cropper.min.css'); ?>
<?= HtmlHelper::cssFile('@web/resources/plugins/cropper/sitelogo.css'); ?>

<div class="row">
    <div class="col-sm-3">
        <div class="box">
            <div class="modal-body text-center">
            <p><img class="profile-user-img img-responsive img-circle rf-img-lg" alt="image" src="<?= HtmlHelper::headPortrait($model->head_portrait);?>" onerror="this.src='<?= HtmlHelper::onErrorImg();?>'"></p>
            <p><a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#avatar-modal"> <i class="fa fa-upload"></i> 头像更改</a></p>
                <p class="profile-username"><?= $model->username; ?></p>
            <p>最后登陆IP : <?= $model->last_ip ?></p>
            <p>最后登陆时间 : <?= Yii::$app->formatter->asDatetime($model->last_time) ?></p>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'head_portrait')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'realname')->textInput() ?>
                <?= $form->field($model, 'sex')->radioList(['1' => '男','2' => '女']) ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= \backend\widgets\provinces\Provinces::widget([
                    'form' => $form,
                    'model' => $model,
                    'provincesName' => 'provinces',// 省字段名
                    'cityName' => 'city',// 市字段名
                    'areaName' => 'area',// 区字段名
                ]); ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model,'birthday')->widget('kartik\date\DatePicker',[
                    'language'  => 'zh-CN',
                    'layout'=>'{picker}{input}',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,// 今日高亮
                        'autoclose' => true,// 选择后自动关闭
                        'todayBtn' => true,// 今日按钮显示
                    ],
                    'options'=>[
                        'class' => 'form-control no_bor',
                        'readonly' => 'readonly',// 禁止输入
                    ]
                ]); ?>
                <?= $form->field($model, 'address')->textarea() ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit" onclick="SendForm()">保存</button>
                <?= $backBtn ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="avatar-form">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">头像上传</h4>
                </div>
                <div class="modal-body">
                    <div class="avatar-body">
                        <div class="avatar-upload">
                            <input class="avatar-src" name="avatar_src" type="hidden">
                            <input class="avatar-data" name="avatar_data" type="hidden">
                            <button class="btn btn-primary"  type="button" style="height: 35px;" onClick="$('input[id=avatarInput]').click();">图片选择</button>
                            <span id="avatar-name" style="display: none"></span>
                            <input class="avatar-input hide" id="avatarInput" name="avatar_file" type="file" accept="image/*">
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="avatar-wrapper"></div>
                            </div>
                            <div class="col-md-3">
                                <div class="avatar-preview preview-lg" id="imageHead"></div>
                                <div class="avatar-preview preview-md"></div>
                                <div class="avatar-preview preview-sm"></div>
                            </div>
                        </div>
                        <div class="row avatar-btns">
                            <div class="col-md-3">
                                <span class="btn btn-white fa fa-undo" data-method="rotate" data-option="-90" title="向左旋转90°"> 左旋转</span>
                                <span class="btn  btn-white fa fa-repeat" data-method="rotate" data-option="90" title="向右旋转90°"> 右旋转</span>
                            </div>
                            <div class="col-md-6" style="text-align: right;">
                                <div class="btn btn-white fa fa-arrows" data-method="setDragMode" data-option="move" title="移动"> 移动</div>
                                <div class="btn btn-white fa fa-crop" data-method="setDragMode" data-option="crop" title="裁剪"> 裁剪</div>
                                <div class="btn btn-white fa fa-search-plus" data-method="zoom" data-option="0.1" title="放大图片"> 放大</div>
                                <div class="btn btn-white fa fa-search-minus" data-method="zoom" data-option="-0.1" title="缩小图片"> 缩小</div>
                                <div type="button" class="btn btn-white fa fa-refresh" data-method="reset" title="重置图片"> 重置</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary avatar-save" data-dismiss="modal">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php Yii::$app->view->registerJsFile('@web/resources/dist/js/bootstrap.min.js?v=3.3.7'); ?>
<?= HtmlHelper::jsFile('@web/resources/plugins/cropper/cropper.js')?>
<?= HtmlHelper::jsFile('@web/resources/plugins/cropper/sitelogo.js')?>
<?= HtmlHelper::jsFile('@web/resources/plugins/cropper/html2canvas.min.js')?>

<script type="text/javascript">
    // 做个下简易的验证  大小 格式
    $('#avatarInput').on('change', function(e) {
        var filemaxsize = 1024 * 5;// 5M
        var target = $(e.target);
        var Size = target[0].files[0].size / 1024;
        if(Size > filemaxsize) {
            rfError('图片过大，请重新选择!');
            $(".avatar-wrapper").children().remove;
            return false;
        }
        if(!this.files[0].type.match(/image.*/)) {
            rfError('请选择正确的图片!');
            return false;
        } else {
            var filename = document.querySelector("#avatar-name");
            var texts = document.querySelector("#avatarInput").value;
            var teststr = texts; // 你这里的路径写错了
            testend = teststr.match(/[^\\]+\.[^\(]+/i); // 直接完整文件名的
            filename.innerHTML = testend;
        }
    });

    $(".avatar-save").on("click", function() {
        // 截图小的显示框内的内容
        var targetDom = $("#imageHead");
        var copyDom = targetDom.clone();
        copyDom.width(targetDom.width() + "px");
        copyDom.height(targetDom.height() + "px");
        $('body').append(copyDom);
        html2canvas(copyDom, {
            allowTaint  : true,
            taintTest   : false,
            onrendered  : function(canvas) {
                canvas.id = "mycanvas";
                var dataUrl = canvas.toDataURL();
                var base64 = dataUrl.split(',');
                copyDom.remove();
                imagesAjax(base64[1]);
            }
        });
    });

    function imagesAjax(src) {
        var data = {};
        data.image = src;
        data.jid = $('#jid').val();
        data.drive = 'local';
        $.ajax({
            url : "<?= Url::to(['/file/base64'])?>",
            type : "post",
            dataType : 'json',
            data : data,
            success : function(data) {
                if(data.code == 200) {
                    data = data.data;
                    $('#manager-head_portrait').val(data.url);
                    $('.img-circle').attr('src',data.url);
                }else{
                    rfError(data.message)
                }
            }
        });
    }

    // 提交表单时候触发
    function SendForm(){
        var status = "<?= Yii::$app->user->id == $model->id ? true : false ;?>";
        if(status){
            var src = $('#manager-head_portrait').val();
            if(src){
                $('.head_portrait',window.parent.document).attr('src',src);
            }
        }
    }
</script>