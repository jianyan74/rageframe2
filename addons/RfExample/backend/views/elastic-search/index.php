<?php

use yii\widgets\LinkPager;
use common\helpers\AddonUrl;

$this->title = 'ElasticSearch';
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
                        <th>内容</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <?php $tmpModel = $model['_source'] ?>
                        <tr id = <?= $model['_id']; ?>>
                            <td><?= $model['_id']; ?></td>
                            <td><?= $tmpModel['title']?></td>
                            <td class="col-md-1"><input type="text" class="form-control" value="<?= $tmpModel['sort']?>" onblur="rfSort(this)"></td>
                            <td><?= $tmpModel['content']?></td>
                            <td>
                                <a href="<?= AddonUrl::to(['edit','id' => $model['_id']])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp
                                <?= \common\helpers\HtmlHelper::status($tmpModel['status'] ?? 1);?>
                                <a href="<?= AddonUrl::to(['delete','id'=> $model['_id']])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
                            </td>
                        </tr>
                    <?php } ?>
                    <tr><td colspan="4"> 注意: 添加修改有1s延迟</td></tr>
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
                            'prevPageLabel' => "上一页",
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>