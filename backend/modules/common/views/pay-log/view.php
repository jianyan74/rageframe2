<?php

use common\enums\PayTypeEnum;
use common\helpers\Html;
use common\enums\StatusEnum;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-hover text-center">
        <tbody>
        <tr>
            <td>支付编号</td>
            <td><?= Html::encode($model['out_trade_no']) ?></td>
        </tr>
        <tr>
            <td>支付金额</td>
            <td>
                应付金额：<?= $model->total_fee ?><br>
                实际支付：<?= $model->pay_fee ?>
            </td>
        </tr>
        <tr>
            <td>支付来源</td>
            <td>
                订单编号：<?= Html::encode($model->order_sn); ?><br>
                订单类型：<?= Html::encode($model->order_group); ?>
            </td>
        </tr>
        <tr>
            <td>支付类型</td>
            <td><?= PayTypeEnum::getValue($model['pay_type']); ?></td>
        </tr>
        <tr>
            <td>商户号</td>
            <td><?= Html::encode($model['mch_id']) ?></td>
        </tr>
        <tr>
            <td>
                回执订单号<br>
                (进行退款或其他操作)
            </td>
            <td><?= Html::encode($model['transaction_id']) ?></td>
        </tr>
        <tr>
            <td>交易类型</td>
            <td><?= Html::encode($model['trade_type']) ?></td>
        </tr>
        <tr>
            <td>状态</td>
            <td>
                <?php if ($model['pay_status'] == StatusEnum::ENABLED) { ?>
                    <span class="label label-primary">支付成功</span>
                <?php } else { ?>
                    <span class="label label-danger">未支付</span>
                <?php } ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>