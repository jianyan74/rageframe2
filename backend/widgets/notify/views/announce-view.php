<?php
$this->title = '公告详情';
$this->params['breadcrumbs'][] = ['label' => '公告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

use common\helpers\Url;

?>

<div class="row">
    <div class="col-sm-2">
        <div class="box box-solid p-xs rfAddonMenu">
            <div class="box-header with-border">
                <h3 class="rf-box-title">消息提醒</h3>
            </div>
            <div class="box-body no-padding">
                <?= $this->render('_nav') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-10">
        <div class="box">
            <div class="box-body">
                <div class="mailbox-read-info">
                    <h3><?= $model['notifySenderForMember']['title']; ?></h3>
                    <h5>来自: <?= $model->notifySenderForMember->senderForManager->username ?? ''; ?>
                        <span class="mailbox-read-time pull-right"><?= Yii::$app->formatter->asDatetime($model['created_at']); ?></span>
                    </h5>
                </div>
                <!-- /.mailbox-controls -->
                <div class="mailbox-read-message">
                    <p><?= \common\helpers\Html::decode($model['notifySenderForMember']['content']); ?></p>
                </div>
            </div>
            <div class="box-footer text-center">
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>
