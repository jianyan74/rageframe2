<?php
use yii\grid\GridView;
use common\helpers\Html;

$this->title = '文件列表';
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
                            'attribute' => 'drive',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function($model) use ($driveExplain) {
                                return $driveExplain[$model->drive];
                            },
                        ],
                        [
                            'attribute' => 'base_url',
                            'filter' => false, //不显示搜索框
                            'value' => function($model){
                                if (($model['upload_type'] == 'images' || preg_match("/^image/", $model['specific_type'])) && $model['extension'] != 'psd') {
                                    return Html::imageFancyBox($model->base_url);
                                }

                                return \yii\helpers\Html::a('在线预览', $model->base_url, [
                                    'target' => '_blank'
                                ]);
                            },
                            'format' => 'raw'
                        ],
                        [
                            'attribute' => 'upload_type',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        'name',
                        [
                            'attribute' => 'size',
                            'filter' => false, //不显示搜索框
                            'value' => function($model) {
                                return Yii::$app->formatter->asShortSize($model->size, 0);
                            },
                        ],
                        [
                            'attribute' => 'upload_ip',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'label'=> '创建日期',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{status} {delete}',
                            'buttons' => [
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['destroy','id' => $model->id]);
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