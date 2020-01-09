<?php

use common\helpers\Url;

$this->title = '首页';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<style>
    .info-box-number {
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-person-stalker blue"></i> <?= $memberCount ?></span>
                <span class="info-box-text">会员人数(个)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-card green"></i> <?= $memberAccount['user_money'] ?? 0 ?></span>
                <span class="info-box-text">会员剩余余额(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-ios-pulse red"></i> <?= abs($memberAccount['consume_money']) ?? 0 ?></span>
                <span class="info-box-text">会员总消费(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-arrow-graph-up-right yellow"></i> <?= $memberAccount['give_money'] ?? 0 ?></span>
                <span class="info-box-text">会员余额总赠送(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-ios-lightbulb-outline magenta"></i> <?= $memberAccount['user_integral'] ?? 0 ?></span>
                <span class="info-box-text">会员剩余积分(个)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-ios-paper-outline cyan"></i> <?= $memberCount ?></span>
                <span class="info-box-text">行为日志(条)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">关注人数统计</h3>
            </div>
            <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['member-between-count']),
                    'height' => '315px'
                ]
            ]) ?>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-md-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">第三方消费统计</h3>
            </div>
            <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['member-credits-log-between-count']),
                    'height' => '315px'
                ]
            ]) ?>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>