<?php
use yii\helpers\Url;

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <?php foreach ($cates as $cate){ ?>
                <li class="<?php if ($cate->id == $cate_id ){ echo 'active' ;}?>"><a href="<?= Url::to(['index','cate_id' => $cate->id])?>"> <?= $cate->title ?></a></li>
            <?php } ?>
            <li><a href="<?= Url::to(['menu-cate/index'])?>"> 菜单分类</a></li>
            <a class="btn btn-primary btn-xs pull-right h6" href="<?= Url::to(['edit', 'cate_id' => $cate_id])?>" data-toggle='modal' data-target='#ajaxModal'>
                <i class="fa fa-plus"></i> 创建
            </a>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="50">折叠</th>
                                <th>标题</th>
                                <th>路由</th>
                                <th>图标</th>
                                <th>开发模式</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?= $this->render('tree', [
                                'models' => $models,
                                'parent_title' =>"无",
                                'pid' => 0,
                                'cate_id' => $cate_id
                            ])?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //折叠
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