<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = '充值日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach ($creditType as $key => $item) { ?>
                    <li <?php if ($credit_type == $key) { ?>class="active"<?php } ?>><a href="<?= Url::to(['index', 'credit_type' => $key]) ?>"> <?= $item; ?></a></li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
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
                                'label' => '关联用户',
                                'attribute' => 'member_id',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeTextInput($searchModel, 'member_id', [
                                        'class' => 'form-control',
                                        'placeholder' => '用户ID'
                                    ]
                                ),
                                'value' => function ($model) {
                                    return "用户ID：" . $model->member->id . '<br>' .
                                        "昵称：" . $model->member->nickname . '<br>' .
                                        "账号：" . $model->member->username . '<br>' .
                                        "手机：" . $model->member->mobile . '<br>';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '变动记录',
                                'attribute' => 'new_num',
                                'filter' => Html::activeTextInput($searchModel, 'new_num', [
                                        'class' => 'form-control',
                                        'placeholder' => '变动数量'
                                    ]
                                ),
                                'value' => function ($model) {

                                    $operational = $model->num < 0 ? '-' : '+';
                                    return $model->old_num . $operational . abs($model->num) . '=' . $model->new_num;
                                },
                            ],
                            'credit_group',
                            'credit_group_detail',
                            'remark',
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
</div>