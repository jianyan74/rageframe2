<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '粉丝标签';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    如果您的公众号类型为："认证订阅号" 或 "认证服务号",您可以使用粉丝标签功能。点击这里 <a href="<?= Url::to(['synchro']) ?>" class="blue">"同步粉丝标签"</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?php $form = ActiveForm::begin([]); ?>
                <div class="col-lg-12">
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
                                <td class="col-md-2"><input type="text" class="form-control" value="<?= $tag['name']?>" name="updateData[<?= $tag['id']?>]"></td>
                                <td><?= $tag['id'] ?></td>
                                <td><?= $tag['count'] ?></td>
                                <td><?= Html::delete(['delete','id' => $tag['id']]);?></td>
                            </tr>
                        <?php } ?>
                        <tr id="position">
                            <td colspan="5"><a href="javascript:void(0);" id="addgroup"><i class="icon ion-android-add-circle"></i> 添加新标签</a></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group">　
                        <div class="col-sm-12 text-center">
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
        html += '<td><input type="text" class="form-control" name="createData[]" placeholder="请填写标签名称"></td>';
        html += '<td>  <a href="javascript:;" onclick="$(this).parent().parent().remove()"> <i class="icon ion-android-cancel"></i></a></td>';
        html += '<td colspan="3"></td>';
        html += '</tr>';
        $('#position').before(html);
    })
</script>