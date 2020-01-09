<?php

use yii\grid\GridView;
use common\helpers\Html;

$this->title = '省市区爬虫日志';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
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
                        [
                            'attribute' => 'data',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                $str = '';
                                $str .= '上级ID：' . $model->data['pid'] . '<br>';
                                $str .= '上级名称：' . $model->data['title'] . '<br>';
                                $str .= '请求链接：' . $model->data['chlidLink'];
                                return $str;
                            },
                        ],
                        'message_id',
                        'max_level',
                        'level',
                        'remark',
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
                                    return Html::linkButton(['retry', 'id' => $model->id], '重新推入队列');
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