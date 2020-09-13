<?php

use common\helpers\Html;
use common\helpers\ImageHelper;
use common\enums\MemberAuthEnum;
use yii\grid\GridView;

$this->title = '后台用户';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
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
                            'attribute' => 'head_portrait',
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->head_portrait)),
                                    [
                                        'class' => 'img-circle rf-img-md img-bordered-sm',
                                    ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        'attribute' => 'username',
                        'realname',
                        'mobile',
                        [
                            'label' => '角色',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if ($model->id == Yii::$app->params['adminAccount']) {
                                    return Html::tag('span', '超级管理员', ['class' => 'label label-success']);
                                } else {
                                    if (isset($model->assignment->role->title)) {
                                        return Html::tag('span', $model->assignment->role->title, ['class' => 'label label-primary']);
                                    } else {
                                        return Html::tag('span', '未授权', ['class' => 'label label-default']);
                                    }
                                }
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label'=> '微信绑定',
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',
                            'value' => function($model){
                                if (!empty($model->authWechat)) {
                                    return Html::tag('span', '已绑定',
                                        ['class' => 'label label-primary']);
                                } else {
                                    return Html::tag('span', '未绑定', ['class' => 'label label-default']);
                                }
                            },
                        ],
                        [
                            'label' => '最后登录',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "最后访问IP：" . $model->last_ip . '<br>' .
                                    "最后访问：" . Yii::$app->formatter->asDatetime($model->last_time) . '<br>' .
                                    "登录次数：" . $model->visit_count;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{account} {binding} {edit} {destroy}',
                            'contentOptions' => ['class' => 'text-align-center'],
                            'buttons' => [
                                'account' => function ($url, $model, $key) {
                                    return Html::a('账号密码', ['ajax-edit', 'id' => $model->id], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                            'class' => 'blue'
                                        ]) . '<br>';
                                },
                                'binding' => function ($url, $model, $key) {
                                    if (!empty($model->authWechat)) {
                                        return Html::a('解绑微信', ['un-bind', 'id' => $model->id, 'type' => MemberAuthEnum::WECHAT], [
                                                'class' => 'cyan',
                                            ]) . '<br>';
                                    } else {
                                        return Html::a('绑定微信', ['binding', 'id' => $model->id, 'type' => MemberAuthEnum::WECHAT], [
                                                'class' => 'cyan',
                                                'data-fancybox' => 'gallery',
                                            ]) . '<br>';
                                    }
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::a('编辑', ['edit', 'id' => $model->id], [
                                            'class' => 'purple'
                                        ]) . '<br>';
                                },
                                'destroy' => function ($url, $model, $key)  {
                                    if ($model->id != Yii::$app->params['adminAccount']) {
                                        return Html::a('删除', ['destroy', 'id' => $model->id], [
                                                'class' => 'red',
                                            ]);
                                    }

                                    return '';
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>