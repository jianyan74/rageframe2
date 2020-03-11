<?php
use common\helpers\Html;
use yii\grid\GridView;
use common\enums\StatusEnum;
use common\enums\MerchantStateEnum;

$this->title = '商户管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit'], '创建'); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover rf-table'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        'company_name',
                        'title',
                        [
                            'attribute' => 'cate.title',
                            'label'=> '分类',
                            'filter' => Html::activeDropDownList($searchModel, 'cate_id', $cates, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        'tax_rate',
                        [
                            'label' => '账户金额',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "剩余：" . $model->account->user_money . '<br>' .
                                    "累计：" . $model->account->accumulate_money;
                            },
                            'format' => 'raw',
                        ],
                        'mobile',
                        [
                            'label' => '信息状态',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return $model->status == StatusEnum::ENABLED ? '正常' : '信息待完善';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '状态',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return MerchantStateEnum::getValue($model->state);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{member} {role} {edit} {ajax-edit} {status} {destroy}',
                            'buttons' => [
                                'member' => function ($url, $model, $key) {
                                    return Html::a('用户管理', ['member/index', 'merchant_id' => $model->id], [
                                            'class' => 'blue',
                                    ]) . '<br>';
                                },
                                'role' => function ($url, $model, $key) {
                                    return Html::a('角色管理', ['auth-role/index', 'merchant_id' => $model->id], [
                                        'class' => 'purple',
                                    ]) . '<br>';
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::a('编辑商户', ['edit', 'id' => $model->id], [
                                        'class' => 'green',
                                    ]) . '<br>';
                                },
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::a('商户状态', ['ajax-edit', 'id' => $model->id], [
                                            'class' => 'orange',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]) . '<br>';
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::a('删除商户', ['destroy', 'id' => $model->id], [
                                            'class' => 'red',
                                            'onclick' => "rfDelete(this);return false;"
                                    ]) . '<br>';
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>