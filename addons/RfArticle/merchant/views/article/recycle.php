<?php
use yii\widgets\LinkPager;
use common\helpers\Url;
use common\helpers\Html;

$this->title = '回收站';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
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
                            <td class="col-md-1"><?= Html::sort($model['sort']); ?></td>
                            <td>
                                <?= Html::linkButton(['show','id' => $model->id], '还原'); ?>
                                <?= Html::delete(['delete','id' => $model->id]); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <?= LinkPager::widget([
                    'pagination' => $pages
                ]);?>
            </div>
        </div>
    </div>
</div>