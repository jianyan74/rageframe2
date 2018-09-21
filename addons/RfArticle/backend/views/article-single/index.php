<?php
use yii\widgets\LinkPager;
use common\helpers\AddonUrl;

$this->title = '单页管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= $this->title; ?></h5>
                <div class="ibox-tools">
                    <a class="btn btn-primary btn-xs" href="<?= AddonUrl::to(['edit'])?>">
                        <i class="fa fa-plus"></i>  创建
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>标题</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id = <?= $model->id; ?>>
                            <td><?= $model->id; ?></td>
                            <td><?= $model->title; ?></td>
                            <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']; ?>" onblur="rfSort(this)"></td>
                            <td>
                                <a href="<?= AddonUrl::to(['edit','id' => $model->id])?>"><span class="btn btn-info btn-sm">编辑</span></a>
                                <?= \common\helpers\HtmlHelper::statusSpan($model['status']); ?>
                                <a href="<?= AddonUrl::to(['delete','id'=>$model->id])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-12">
                        <?= LinkPager::widget([
                            'pagination' => $pages,
                            'maxButtonCount' => 5,
                            'firstPageLabel' => "首页",
                            'lastPageLabel' => "尾页",
                            'nextPageLabel' => "下一页",
                            'prevPageLabel'=> "上一页",
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>