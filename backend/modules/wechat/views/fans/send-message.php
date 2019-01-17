<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
                    <img src="<?= $model->head_portrait ?>" class="img-circle img-bordered-sm rf-img-lg">
                </td>
                <td><?= $model['nickname']?></td>
            </tr>
            <tr>
                <td>粉丝编号</td>
                <td><?= $model['openid']?></td>
            </tr>
            <tr>
                <td>性别</td>
                <td><?= $model->sex == 1 ? '男' : '女' ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true" onclick="setType(1)">内容</a></li>
                    <li><a data-toggle="tab" href="#tab-2" aria-expanded="false" onclick="setType(2)">图片</a></li>
                    <li><a data-toggle="tab" href="#tab-3" aria-expanded="false" onclick="setType(3)">图文</a></li>
                    <li><a data-toggle="tab" href="#tab-4" aria-expanded="false" onclick="setType(4)">视频</a></li>
                    <li><a data-toggle="tab" href="#tab-5" aria-expanded="false" onclick="setType(5)">音频</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <?= Html::textarea('content', '', [
                                'class' => 'form-control',
                                'id' => 'content',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <?= \backend\widgets\wechatselectattachment\Select::widget([
                                'name' => 'images',
                                'value' => '',
                                'type' => 'image',
                                'label' => '图片',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <?= \backend\widgets\wechatselectattachment\Select::widget([
                                'name' => 'news',
                                'value' => '',
                                'type' => 'news',
                                'label' => '图文',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-4" class="tab-pane">
                        <div class="panel-body">
                            <div class="form-group required">
                                <label class="control-label" for="manager-mobile">标题</label>
                                <?= Html::input('text', 'title', '', [
                                    'class' => 'form-control',
                                    'id' => 'title',
                                ])?>
                            </div>
                            <div class="form-group required">
                                <label class="control-label" for="manager-mobile">视频说明</label>
                                <?= Html::textarea('description', '',[
                                    'class' => 'form-control',
                                    'id' => 'description',
                                ])?>
                            </div>
                            <?= \backend\widgets\wechatselectattachment\Select::widget([
                                'name' => 'video',
                                'value' => '',
                                'type' => 'video',
                                'label' => '视频',
                            ])?>
                        </div>
                    </div>
                    <div id="tab-5" class="tab-pane">
                        <div class="panel-body">
                            <?= \backend\widgets\wechatselectattachment\Select::widget([
                                'name' => 'voice',
                                'value' => '',
                                'type' => 'voice',
                                'label' => '语音',
                            ])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= Html::hiddenInput('type', '1', [
        'id' => 'type'
])?>

<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <span class="btn btn-primary" onclick="beforSubmit()">发送消息</span>
</div>
<?php ActiveForm::end(); ?>

<script>
    var type = 1;// 1:文字;2:图片;3:图文;4:视频;5:音频;

    // 设置类型
    function setType(num) {
        type = num;
        $('#type').val(num)
    }

    function beforSubmit() {
        var val = description = title = '';
        var id = "<?= $model['id']; ?>";
        if (type == 1 && !$('#content').val()) {
            rfWarning('请填写内容');
            return false;
        }

        if (type == 2 && !$("input[name='images']").val()) {
            rfWarning('请选择图片');
            return false;
        }

        if (type == 3 && !$("input[name='news']").val()) {
            rfWarning('请选择图文');
            return false;
        }

        if (type == 4) {

            if (!$('#title').val()){
                rfWarning('请选择标题');
                return false;
            }

            if (!$('#description').val()){
                rfWarning('请填写内容');
                return false;
            }

            if (!$("input[name='video']").val()) {
                rfWarning('请选择视频');
                return false;
            }
        }

        if (type == 5 && !$("input[name='voice']").val()) {
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