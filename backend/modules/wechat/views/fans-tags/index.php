<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '粉丝标签';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>粉丝标签</h5>
                    <div class="pull-right">
                        如果您的公众号类型为："认证订阅号" 或 "认证服务号",您可以使用粉丝标签功能。点击这里 <a href="<?= Url::to(['synchro'])?>">"同步粉丝标签"</a>
                    </div>
                </div>
                <?php $form = ActiveForm::begin([]); ?>
                    <div class="ibox-content">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>标签名称</th>
                                <th>标签id</th>
                                <th>标签内用户数量</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($tags as $key => $tag){ ?>
                                <tr>
                                    <td class="col-md-2"><input type="text" class="form-control" value="<?= $tag['name']?>" name="tag_update[<?= $tag['id']?>]"></td>
                                    <td><?= $tag['id'] ?></td>
                                    <td><?= $tag['count'] ?></td>
                                    <td>
                                        <a href="<?= Url::to(['delete','id' => $tag['id']])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr id="position">
                                <td colspan="5"><a href="javascript:;" id="addgroup"><i class="fa fa-plus-circle"></i> 添加新标签</a></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group">　
                            <div class="col-sm-12 text-center">
                                <div class="hr-line-dashed"></div>
                                <button class="btn btn-primary" type="submit">保存</button>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('#addgroup').click(function(){
        var html = '<tr>';
        html += '<td><input type="text" class="form-control" name="tag_add[]" placeholder="请填写标签名称"></td>';
        html += '<td>  <a href="javascript:;" onclick="$(this).parent().parent().remove()"> <i class="fa fa-times-circle"></i></a></td>';
        html += '<td colspan="3"></td>';
        html += '</tr>';
        $('#position').before(html);
    })
</script>