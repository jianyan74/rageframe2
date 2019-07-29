<?php
use yii\grid\GridView;
use common\helpers\Url;
use common\enums\GenderEnum;
use common\helpers\Html;

$this->title = 'Curd Grid';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="pull-right">
                    <?= Html::create(['edit'], '创建', [
                            'class' => 'btn btn-primary btn-xs openIframe',
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
                        'title',
                        // 'cate_id',
                        'sort',
                        // 'position',
                        [
                            'attribute' => 'sex',
                            'value' => function ($model, $key, $index, $column){
                                return $model->sex == 1 ? '男' : '女';
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'sex', GenderEnum::$listExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        // 'content:ntext',
                        // 'cover',
                        // 'covers',
                        // 'attachfile',
                        'keywords',
                        // 'description',
                        // 'price',
                        // 'views',
                        // 'stat_time:datetime',
                        // 'end_time:datetime',
                        // 'status',
                        // 'email:email',
                        // 'provinces',
                        // 'city',
                        // 'area',
                        // 'ip',
                        [
                            'label'=>'创建日期',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        // 'updated_at',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit','id' => $model->id], '编辑', [
                                        'class' => 'btn btn-primary btn-sm openIframe',
                                    ]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete','id' => $model->id]);
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