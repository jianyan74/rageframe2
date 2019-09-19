<?php

use yii\grid\GridView;
use common\helpers\Html;

$this->title = '省市区爬虫';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="pull-right">
                    <?= Html::linkButton(['map'], '经纬度爬取', [
                        'onclick' => "rfTwiceAffirm(this, '确认爬取经纬度吗？', '请确保省市区数据完整, 且配置了百度ak');return false;",
                        'class' => 'btn btn-primary btn-xs',
                    ]); ?>
                    <?= Html::create(['create'], '创建', [
                        'class' => 'btn btn-primary btn-xs',
                    ]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        'year',
                        [
                            'attribute' => 'max_level',
                            'value' => function ($model, $key, $index, $column) use ($maxLevelExplain) {
                                return $maxLevelExplain[$model->max_level];
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'max_level', $maxLevelExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        'message_id',
                        [
                            'label' => '创建日期',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{list}',
                            'buttons' => [
                                'list' => function ($url, $model, $key) {
                                    return Html::linkButton(['province-gather-log/index', 'job_id' => $model->id], '报错日志');
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>