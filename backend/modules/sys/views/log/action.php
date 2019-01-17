<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\HtmlHelper;

$this->title = '行为日志';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <?= $this->render('_nav', [
                'type' => 'action'
            ]) ?>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>行为</th>
                            <th>用户</th>
                            <th>模块</th>
                            <th>Url</th>
                            <th>IP</th>
                            <th>地区</th>
                            <th>说明</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr id = <?= $model->id; ?>>
                                <td><?= $model->id; ?></td>
                                <td><?= $model->behavior; ?></td>
                                <td><?= isset($model->manager->username) ? $model->manager->username : '游客' ?></td>
                                <td><?= HtmlHelper::encode($model->module); ?></td>
                                <td><?= HtmlHelper::encode($model->url); ?></td>
                                <td><?= long2ip($model->ip); ?></td>
                                <td>
                                    <?php if (long2ip($model->ip) == '127.0.0.1'){ ?>
                                        本地
                                    <?php }else{ ?>
                                        <?= $model->country; ?>·<?= $model->provinces; ?>·<?= $model->city; ?>
                                    <?php } ?>
                                </td>
                                <td><?= HtmlHelper::encode($model->remark); ?></td>
                                <td><?= Yii::$app->formatter->asDatetime($model->created_at); ?></td>
                                <td>
                                    <?= HtmlHelper::linkButton(['action-view','id' => $model->id], '查看详情', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ])?>
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