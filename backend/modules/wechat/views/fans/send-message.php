<?php
use yii\helpers\Url;

$this->title = '发送消息';
$this->params['breadcrumbs'][] = ['label' => '粉丝管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本信息</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-md-12">
                        <table class="table text-center">
                            <tbody>
                            <tr>
                                <td class="feed-element">
                                    <img src="<?= $model->head_portrait ?>" class="img-circle">
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
                    <div class="col-md-12">
                        <div class="form-group field-sendform-content">
                            <label class="control-label" for="sendform-content">内容</label>
                            <textarea id="sendform-content" class="form-control" name="content"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <span class="btn btn-primary" onclick="beforSubmit()">发送消息</span>
                            <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                        </div>
                    </div>　
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function beforSubmit() {
        var id = "<?= $model['id']; ?>";
        var val =  $('#sendform-content').val();
        if (!val){
            rfAffirm('请填写内容');
            return false;
        }

        $.ajax({
            type:"get",
            url:"<?= Url::to(['send-message'])?>",
            dataType: "json",
            data: {
                content:val,
                id: id,
            },
            success: function(data){
                if(data.code == 200) {
                    $('#sendform-content').val('');
                    rfSuccess('发送成功')
                }else{
                    rfWarning(data.message);
                }
            }
        });

    }
</script>