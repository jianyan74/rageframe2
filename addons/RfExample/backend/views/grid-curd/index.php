<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\AddonUrl;

$this->title = 'Curd For Grid';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= $this->title; ?></h5>
                <div class="ibox-tools">
                    <a class="btn btn-primary btn-xs" href="<?= AddonUrl::to(['edit'])?>">
                        <i class="fa fa-plus"></i>  创建
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'layout'=> '{summary}{items}<div class="text-right tooltip-demo">{pager}</div>',
                    'tableOptions' => ['class' => 'table table-hover'],
                    'pager'=>[
                        //'options'=>['class' => 'hidden']//关闭分页
                        'maxButtonCount' => 5,
                        'firstPageLabel' => "首页",
                        'lastPageLabel' => "尾页",
                        'nextPageLabel' => "下一页",
                        'prevPageLabel' => "上一页",
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'title',
                        // 'cate_id',
                        'sort',
                        // 'position',
                        [
                            'attribute' => 'sex',
                            'value'=>function ($model, $key, $index, $column){
                                return $model->sex == 1 ? '男' : '女';
                            },
                            'filter' => Html::activeDropDownList($searchModel,
                                'sex',[
                                    '1' => '男',
                                    '2' => '女'
                                ],
                                [
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
                                    return Html::a(Html::tag('span', '编辑', ['class' => "btn btn-info btn-sm"]), AddonUrl::to(['edit','id' => $model->id]));
                                },
                                'status' => function ($url, $model, $key) {
                                    return $model['status'] == 0 ? '<span class="btn btn-primary btn-sm" onclick="rfStatus(this)">启用</span>': '<span class="btn btn-default btn-sm"  onclick="rfStatus(this)">禁用</span>';
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::a(Html::tag('span', '删除', ['class' => "btn btn-warning btn-sm"]), AddonUrl::to(['delete','id' => $model->id]),['onclick' => "rfDelete(this);return false;"]);
                                },
                            ],
                        ],
                    ],

                ]); ?>
            </div>
        </div>
    </div>
</div>