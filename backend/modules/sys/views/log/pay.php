<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '行为日志';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
        <?= $this->render('_nav', [
            'type' => 'pay'
        ]) ?>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <form action="" method="get" class="form-horizontal" role="form" id="form">
                                <div class="col-sm-5">
                                    <?= Html::dropDownList('pay_status', $pay_status, ['' => '全部', 0 => '未支付', 1 => '已支付'], ['class' => 'form-control']);?>
                                </div>
                                <div class="input-group m-b">
                                    <input type="text" class="form-control" name="keyword" placeholder="<?= $keyword ? $keyword : '请输入支付编号/订单编号'?>"/>
                                    <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>支付编号</th>
                                <th>支付金额</th>
                                <th>支付来源</th>
                                <th>支付类型</th>
                                <th>状态</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($models as $model){ ?>
                                <tr id = <?= $model->id; ?>>
                                    <td><?= $model->id; ?></td>
                                    <td><?= $model->out_trade_no; ?></td>
                                    <td>
                                        应付金额：<?= $model->total_fee > 0 ? $model->total_fee / 100 : 0 ; ?><br>
                                        实际支付：<?= $model->pay_fee > 0 ? $model->pay_fee / 100 : 0 ; ?>
                                    </td>
                                    <td>
                                        订单编号：<?= $model->order_sn; ?><br>
                                        订单类型：<?= \common\models\common\PayLog::$orderGroupExplain[$model->order_group]; ?>
                                    </td>
                                    <th><?= \common\models\common\PayLog::$payTypeExplain[$model->pay_type]; ?></th>
                                    <td>
                                        <?php if($model->pay_status == \common\enums\StatusEnum::ENABLED){ ?>
                                            <span class="label label-primary">支付成功</span>
                                        <?php }else{ ?>
                                            <span class="label label-danger">未支付</span>
                                        <?php } ?>
                                    </td>
                                    <td><?= Yii::$app->formatter->asDatetime($model->created_at); ?></td>
                                    <td>
                                        <a href="<?= Url::to(['pay-view','id' => $model->id])?>" data-toggle='modal' data-target='#ajaxModalLg'><span class="btn btn-info btn-sm">查看详情</span></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12">
                                <?= LinkPager::widget([
                                    'pagination' => $pages,
                                    'maxButtonCount' => 5,
                                    'firstPageLabel' => "首页",
                                    'lastPageLabel' => "尾页",
                                    'nextPageLabel' => "下一页",
                                    'prevPageLabel' => "上一页",
                                ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>