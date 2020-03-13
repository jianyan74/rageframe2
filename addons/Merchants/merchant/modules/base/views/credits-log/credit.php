<?php

use yii\grid\GridView;
use common\helpers\Html;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'label' => '变动数量',
                            'attribute' => 'num',
                        ],
                        'remark',
                        [
                            'label' => '变动说明',
                            'attribute' => 'new_num',
                            'value' => function ($model) {
                                $operational = $model->num < 0 ? '-' : '+';
                                return $model->old_num . $operational . abs($model->num) . '=' . $model->new_num;
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
