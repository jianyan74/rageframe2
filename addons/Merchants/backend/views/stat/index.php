<?php

use common\helpers\Url;

$this->title = '商户统计';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<style>
    .info-box-number {
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-person-stalker blue"></i> <?= $merchantCount ?></span>
                <span class="info-box-text">商户数(个)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-card green"></i> <?= $merchantAccount['user_money'] ?? 0 ?></span>
                <span class="info-box-text">商户剩余余额(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-ios-pulse red"></i> <?= $merchantAccount['accumulate_money'] - $merchantAccount['user_money'] ?></span>
                <span class="info-box-text">商户累计提现(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-arrow-graph-up-right yellow"></i> <?= $merchantAccount['accumulate_money'] ?? 0 ?></span>
                <span class="info-box-text">商户累计余额(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>