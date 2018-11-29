<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '行为日志';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
        <?= $this->render('_nav', [
            'type' => 'action'
        ]) ?>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    <div class="col-sm-12">
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
                                    <td><?= $model->module; ?></td>
                                    <td><?= $model->url; ?></td>
                                    <td><?= long2ip($model->ip); ?></td>
                                    <td>
                                        <?php if (long2ip($model->ip) == '127.0.0.1'){ ?>
                                            本地
                                        <?php }else{ ?>
                                            <?= $model->country; ?>·<?= $model->provinces; ?>·<?= $model->city; ?>
                                        <?php } ?>
                                    </td>
                                    <td><?= \yii\helpers\Html::encode($model->remark); ?></td>
                                    <td><?= Yii::$app->formatter->asDatetime($model->created_at); ?></td>
                                    <td>
                                        <a href="<?= Url::to(['action-view','id' => $model->id])?>" data-toggle='modal' data-target='#ajaxModalLg'><span class="btn btn-info btn-sm">查看详情</span></a>
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
                                    'prevPageLabel' => "上一页",
                                ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>