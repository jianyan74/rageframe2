<?php
use common\helpers\Html;
use yii\widgets\LinkPager;
use common\helpers\Url;

$this->title = 'ElasticSearch';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit']); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
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
                                <?= Html::edit(['edit', 'id' => $model['_id']]); ?>
                                <?= Html::status($tmpModel['status'] ?? 1); ?>
                                <?= Html::delete(['delete', 'id' => $model['_id']]); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr><td colspan="4"> 注意: 添加修改有1s延迟</td></tr>
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