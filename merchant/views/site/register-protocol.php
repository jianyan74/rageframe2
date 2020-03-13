<?php

use yii\helpers\Html;

$this->title = '商户入驻协议';

?>

<div class="row">
    <!-- /.col -->
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">《<?= $this->title; ?>》</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <!-- /.mailbox-read-info -->
                <!-- /.mailbox-controls -->
                <div class="mailbox-read-message">
                    <p><?= Yii::$app->debris->backendConfig('merchant_register_protocol'); ?></p>
                </div>
                <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /. box -->
    </div>
    <!-- /.col -->
</div>