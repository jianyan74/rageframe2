<?php
use yii\grid\GridView;
use yii\helpers\Html as BaseHtml;
use common\helpers\Html;
use common\helpers\ImageHelper;

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
                        ],
                        [
                            'attribute' => 'drive',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeDropDownList($searchModel, 'drive', $driveExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function($model) use ($driveExplain) {
                                return $driveExplain[$model->drive];
                            },
                        ],
                        [
                            'attribute' => 'base_url',
                            'filter' => false, //不显示搜索框
                            'value' => function($model){
                                if (($model['upload_type'] == 'images' || preg_match("/^image/", $model['specific_type'])) && $model['extension'] != 'psd') {
                                    return ImageHelper::fancyBox($model->base_url);
                                }

                                return BaseHtml::a('预览', $model->base_url, [
                                    'target' => '_blank'
                                ]);
                            },
                            'format' => 'raw'
                        ],
                        [
                            'attribute' => 'upload_type',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeDropDownList($searchModel, 'upload_type', $uploadTypeExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function($model) use ($uploadTypeExplain) {
                                return $uploadTypeExplain[$model->upload_type];
                            },
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
                            'value' => function($model) {
                                return long2ip($model->upload_ip);
                            },
                        ],
                        [
                            'label'=> '创建时间',
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
                                    return Html::delete(['destroy', 'id' => $model->id]);
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