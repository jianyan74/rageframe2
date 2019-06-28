<?php
use yii\grid\GridView;
use common\helpers\Html;
use common\enums\WhetherEnum;

$this->title = '短信日志';
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
                            'visible' => true, // 不显示#
                        ],
                        'mobile',
                        'code',
                        'content',
                        'usage',
                        'used',
                        [
                            'attribute' => 'used',
                            'value' => function ($model, $key, $index, $column){
                                return Html::whether($model->used);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'used', WhetherEnum::$listExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        [
                            'attribute' => 'use_time',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'attribute' => 'error_code',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'attribute' => 'error_msg',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'attribute' => 'error_data',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'label'=> '创建时间',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ]
                    ],
                ]); ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>