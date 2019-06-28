<?php
use common\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\selector\Select;
?>

<?php $form = ActiveForm::begin([
        'id' => 'sendMessage'
]); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">发送消息</h4>
</div>

<div class="modal-body">
    <div class="col-md-12">
        <table class="table text-center">
            <tbody>
            <tr>
                <td class="feed-element">
                    <img src="<?= $model->head_portrait ?>" class="img-circle img-bordered-sm" width="48" height="48">
                </td>
                <td><?= $model['nickname']?></td>
            </tr>
            <tr>
                <td>粉丝编号</td>
                <td><?= $model['openid']?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="nav-tabs-custom" style="box-shadow: 0 1px 1px rgba(0,0,0,0);">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true" onclick="setType('text')">内容</a></li>
                    <li><a data-toggle="tab" href="#tab-2" aria-expanded="false" onclick="setType('image')">图片</a></li>
                    <li><a data-toggle="tab" href="#tab-3" aria-expanded="false" onclick="setType('news')">图文</a></li>
                    <li><a data-toggle="tab" href="#tab-4" aria-expanded="false" onclick="setType('video')">视频</a></li>
                    <li><a data-toggle="tab" href="#tab-5" aria-expanded="false" onclick="setType('voice')">音频</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <?= Html::textarea('content', '', [
                                'class' => 'form-control',
                                'id' => 'text',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <?= Select::widget([
                                'name' => 'image',
                                'type' => 'image',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <?= Select::widget([
                                'name' => 'news',
                                'type' => 'news',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-4" class="tab-pane">
                        <div class="panel-body">
                            <?= Select::widget([
                                'name' => 'video',
                                'type' => 'video',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-5" class="tab-pane">
                        <div class="panel-body">
                            <?= Select::widget([
                                'name' => 'voice',
                                'type' => 'voice',
                            ])?>
                        </div>
                    </div>
                    <div class="col-sm-12">注意：三天内有互动的才可发送消息</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= Html::hiddenInput('type', 'text', ['id' => 'type'])?>

<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <span class="btn btn-primary" onclick="beforSubmit()">发送消息</span>
</div>
<?php ActiveForm::end(); ?>

<script>
    // 设置类型
    function setType(type) {
        $('#type').val(type)
    }

    function beforSubmit() {
        var val = description = title = '';
        var id = "<?= $model['id']; ?>";
        var type = $('#type').val();

        if (type == 'text' && !$('#text').val()) {
            rfWarning('请填写内容');
            return false;
        }

        if (type == 'image' && !$("input[name='image']").val()) {
            rfWarning('请选择图片');
            return false;
        }

        if (type == 'news' && !$("input[name='news']").val()) {
            rfWarning('请选择图文');
            return false;
        }

        if (type == 'video' && !$("input[name='video']").val()) {
            rfWarning('请选择视频');
            return false;
        }

        if (type == 'voice' && !$("input[name='voice']").val()) {
            rfWarning('请选择语音');
            return false;
        }

        $.ajax({
            type:"post",
            url:"<?= Url::to(['/wechat/fans/send-message', 'openid' => $model->openid])?>",
            dataType: "json",
            data: $("#sendMessage").serialize(),
            success: function(data){
                if(data.code == 200) {
                    $('.close').click();
                    rfAffirm('发送成功');
                }else{
                    rfWarning(data.message);
                }
            }
        });
    }
</script>