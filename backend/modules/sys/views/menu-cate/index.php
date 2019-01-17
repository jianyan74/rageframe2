<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\HtmlHelper;

$this->title = '菜单分类';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach ($models as $key => $model){ ?>
                    <li><a href="<?= Url::to(['menu/index', 'cate_id' => $model->id])?>"> <?= $model->title ?></a></li>
                <?php } ?>
                <li class="active"><a href="<?= Url::to(['menu-cate/index'])?>"> 菜单分类</a></li>
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
                            <th>#</th>
                            <th>标题</th>
                            <th>图标</th>
                            <th>排序</th>
                            <th>默认显示</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr id = <?= $model->id?>>
                                <td><?= $model->id?></td>
                                <td><?= $model->title ?></td>
                                <td><i class="fa <?= $model->icon ?>"></i></td>
                                <td class="col-md-1"><?= HtmlHelper::sort($model['sort']); ?></td>
                                <td><?= HtmlHelper::whether($model->is_default_show) ?></td>
                                <td>
                                    <?= HtmlHelper::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ])?>
                                    <?= HtmlHelper::status($model['status']); ?>
                                    <?= HtmlHelper::delete(['delete', 'id' => $model->id]); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= LinkPager::widget([
                                'pagination' => $pages,
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>