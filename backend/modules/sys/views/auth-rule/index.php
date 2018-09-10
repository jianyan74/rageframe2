<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\sys\AuthRule;

$this->title = '规则管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>规则管理</h5>
                    <div class="ibox-tools">
                        <a class="btn btn-primary btn-xs" href="<?= Url::to(['ajax-edit'])?>" data-toggle="modal" data-target="#ajaxModal">
                            <i class="fa fa-plus"> 创建</i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>规则名称</th>
                            <th>规则类名</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($models as $model){ ?>
                            <tr>
                                <td><?= $model->name; ?></td>
                                <td><?= AuthRule::getClassName($model->data)?></td>
                                <td><?= Yii::$app->formatter->asDatetime($model->created_at)?></td>
                                <td>
                                    <a href="<?= Url::to(['ajax-edit', 'name' => $model->name])?>" data-toggle="modal" data-target="#ajaxModal">
                                        <span class="btn btn-info btn-sm">编 辑</span>
                                    </a>
                                    <a href="<?= Url::to(['delete','name'=>$model->name])?>" onclick="rfDelete(this);return false;">
                                        <span class="btn btn-warning btn-sm">删除</span>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <?= LinkPager::widget([
                                'pagination' => $pages,
                                'maxButtonCount' => 5,
                                'firstPageLabel' => '首页',
                                'lastPageLabel' => '尾页',
                                'nextPageLabel' => '下页',
                                'prevPageLabel' => '上页',
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>