<?php
use yii\helpers\Url;

$this->title = '菜单分类';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <?php foreach ($models as $key => $model){ ?>
                <li><a href="<?php echo Url::to(['menu/index', 'cate_id' => $model->id])?>"> <?php echo $model->title ?></a></li>
            <?php } ?>
            <li class="active"><a href="<?php echo Url::to(['menu-cate/index'])?>"> 菜单分类</a></li>
            <a class="btn btn-primary btn-xs pull-right h6" href="<?php echo Url::to(['ajax-edit'])?>" data-toggle='modal' data-target='#ajaxModal'>
                <i class="fa fa-plus"></i>  创建
            </a>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>标题</th>
                                <th>图标</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($models as $model){ ?>
                                <tr id = <?php echo $model->id?>>
                                    <td><?php echo $model->id?></td>
                                    <td><?php echo $model->title ?></td>
                                    <td><i class="fa <?php echo $model->icon ?>"></i></td>
                                    <td class="col-md-1"><input type="text" class="form-control" value="<?php echo $model['sort']?>" onblur="rfSort(this)"></td>
                                    <td>
                                        <a href="<?php echo Url::to(['ajax-edit','id'=>$model->id])?>" data-toggle='modal' data-target='#ajaxModal'><span class="btn btn-info btn-sm">编辑</span></a>
                                        <?php echo \common\helpers\HtmlHelper::statusSpan($model['status']); ?>
                                        <a href="<?php echo Url::to(['delete','id'=>$model->id])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>