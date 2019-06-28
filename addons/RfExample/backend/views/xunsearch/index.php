<?php

use yii\widgets\LinkPager;
use common\helpers\Url;

$this->title = 'Xunsearch';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>查询</h5>
            </div>
            <div class="ibox-content">
                <form action="" method="get" class="form-horizontal" role="form" id="form">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-2 col-md-2 control-label">关键字</label>
                        <div class="col-sm-8 col-xs-12 input-group m-b">
                            <input type="text" class="form-control" name="keyword" value="<?= $keyword?>" />
                            <input type="hidden" class="form-control" name="addon" value="<?= Yii::$app->params['addon']['name'] ?>" />
                            <input type="hidden" class="form-control" name="route" value="<?= Yii::$app->params['addonInfo']['oldRoute'] ?>" />
                            <span class="input-group-btn">
                                <button class="btn btn-white"><i class="fa fa-search"></i>搜索</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Xunsearch搜索引擎</h5>
                <div class="ibox-tools">
                    <a class="btn btn-primary btn-xs" href="<?= Url::to(['edit'])?>">
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
                        <th>作者</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id = <?= $model->id; ?>>
                            <td><?= $model->id; ?></td>
                            <td><?= $model->title; ?></td>
                            <td><?= $model->author; ?></td>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at); ?></td>
                            <td>
                                <a href="<?= Url::to(['edit','id' => $model->id])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp
                                <a href="<?= Url::to(['delete','id'=> $model->id])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
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
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>