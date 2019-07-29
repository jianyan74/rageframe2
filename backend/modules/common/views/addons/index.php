<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\AddonHelper;

$this->title = '已安装的插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['index']) ?>">已安装的插件</a></li>
                <li><a href="<?= Url::to(['local']) ?>">安装插件</a></li>
                <li><a href="<?= Url::to(['create']) ?>">设计新插件</a></li>
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
                                'attribute' => 'icon',
                                'filter' => false, //不显示搜索框
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model) {
                                    return Html::img(AddonHelper::getAddonIcon($model['name']), [
                                        'class' => 'img-rounded m-t-xs img-responsive',
                                        'width' => '64',
                                        'height' => '64',
                                    ]);
                                },
                                'format' => 'raw'
                            ],
                            [
                                'attribute' => 'title',
                                // 'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    $str = '<h5> ' . $model['title'] . '</h5>';
                                    $str .= "<small>标识 : " . $model['name'] . "</small>";
                                    return $str;
                                },
                                'format' => 'raw'
                            ],
                            [
                                'attribute' => 'version',
                                'filter' => false, //不显示搜索框
                            ],
                            [
                                'attribute' => 'author',
                                'filter' => false, //不显示搜索框
                            ],
                            [
                                'label' => '功能支持',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    $str = '';
                                    $model['is_setting'] == true && $str .= '<span class="label label-info">全局设置</span> ';
                                    $model['is_rule'] == true && $str .= '<span class="label label-info">嵌入规则</span> ';
                                    $model['is_hook'] == true && $str .= '<span class="label label-info">钩子</span>';
                                    return $str;
                                },
                                'format' => 'raw'
                            ],
                            [
                                'label' => '组别',
                                'attribute' => 'group',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) use ($addonsGroup) {
                                    return '<span class="label label-info">' . $addonsGroup[$model->group]['title'] . '</span> ';
                                },
                                'format' => 'raw'
                            ],
                            [
                                'attribute' => 'brief_introduction',
                                'filter' => false, //不显示搜索框
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{install} {upgrade} {edit} {status} {delete}',
                                'buttons' => [
                                    'install' => function ($url, $model, $key) {
                                        return Html::linkButton(['install', 'name' => $model->name, 'data' => false],
                                            '更新配置', [
                                                'onclick' => "rfTwiceAffirm(this, '确认更新配置吗？', '会重载模块的配置和权限, 更新后需要重新授权');return false;"
                                            ]);
                                    },
                                    'upgrade' => function ($url, $model, $key) {
                                        return Html::linkButton(['upgrade', 'name' => $model->name], '数据库升级', [
                                            'onclick' => "rfTwiceAffirm(this, '确认升级数据库吗？', '会执行更新数据库字段升级等功能');return false;",
                                        ]);
                                    },
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'status' => function ($url, $model, $key) {
                                        return Html::status($model->status);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::linkButton(['un-install', 'name' => $model->name], '卸载', [
                                            'class' => 'btn btn-danger btn-sm',
                                            'onclick' => "rfTwiceAffirm(this, '确认卸载插件么？', '请谨慎操作');return false;",
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>