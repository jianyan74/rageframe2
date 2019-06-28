<?php
$this->title = '公告详情';
$this->params['breadcrumbs'][] = ['label' => '公告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">公告详情</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="mailbox-read-info">
                    <h3><?= $model['notifySenderForManager']['title']; ?></h3>
                    <h5>来自: <?= $model->notifySenderForManager->senderForManager->username ?? ''; ?>
                        <span class="mailbox-read-time pull-right"><?= Yii::$app->formatter->asDatetime($model['created_at']); ?></span>
                    </h5>
                </div>
                <!-- /.mailbox-controls -->
                <div class="mailbox-read-message">
                    <p><?= \common\helpers\Html::decode($model['notifySenderForManager']['content']); ?></p>
                </div>
                <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
            <div class="box-footer text-center">
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /. box -->
    </div>
</div>
