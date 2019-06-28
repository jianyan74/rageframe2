<?php
use yii\grid\GridView;
use common\helpers\Url;

$this->title = '私信列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['announce'])?>"> 公告列表</a></li>
                <li class="active"><a href="<?= Url::to(['message'])?>"> 私信列表</a></li>
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
                                'visible' => false, // 不显示#
                            ],
                            'id',
                            [
                                'label'=> '来自',
                                'filter' => false, //不显示搜索框
                                'value' => function($model){
                                    return $model->notifySenderForManager->senderForManager->username ?? '';
                                }
                            ],
                            'notify.content',
                            [
                                'label'=> '创建时间',
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