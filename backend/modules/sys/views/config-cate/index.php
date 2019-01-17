<?php
use yii\helpers\Url;
use common\helpers\HtmlHelper;

$this->title = '配置分类';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['config/index'])?>">配置管理</a></li>
                <li class="active"><a href="<?= Url::to(['config-cate/index'])?>">配置分类</a></li>
                <li class="pull-right">
                    <?= HtmlHelper::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ])?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th width="50">折叠</th>
                            <th>分类名称</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $this->render('tree', [
                            'models' => $models,
                            'parent_title' =>"无",
                            'pid' => 0,
                        ])?>
                        </tbody>
                    </table>
                </div>
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
