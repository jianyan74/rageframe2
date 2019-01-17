<?php
use yii\helpers\Url;
use common\helpers\HtmlHelper;
use yii\widgets\LinkPager;
use common\models\sys\AuthRule;

$this->title = '规则管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= HtmlHelper::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ])?>
                </div>
            </div>
            <div class="box-body table-responsive">
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
                                <?= HtmlHelper::edit(['ajax-edit', 'name' => $model->name,], '编辑', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ])?>
                                <?= HtmlHelper::delete(['delete', 'name' => $model->name])?>
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