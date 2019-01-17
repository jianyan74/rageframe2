<?php
use yii\helpers\Url;
use common\helpers\HtmlHelper;

$this->title = '角色管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= HtmlHelper::create(['edit'], '创建', [
                        'class' => 'btn btn-primary btn-xs pull-right'
                    ])?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="50">折叠</th>
                        <th>角色名称</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?= $this->render('tree', [
                        'models' => $models,
                        'parent_title' => "无",
                        'parent_key' => 0,
                        'treeStat' => $treeStat
                    ])?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // 折叠
    $('.cf').click(function(){
        var self = $(this);
        var id = self.parent().parent().attr('id');
        if(self.hasClass("fa-minus-square")){
            $('.'+id).hide();
            self.removeClass("fa-minus-square").addClass("fa-plus-square");
        } else {
            $('.'+id).show();
            self.removeClass("fa-plus-square").addClass("fa-minus-square");
            $('.'+id).find(".fa-plus-square").removeClass("fa-plus-square").addClass("fa-minus-square");
        }
    });
</script>
