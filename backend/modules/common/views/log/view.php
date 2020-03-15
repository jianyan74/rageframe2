<?php

use common\helpers\Html;
use common\helpers\DebrisHelper;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
        <tr>
            <td>提交方法</td>
            <td><?= Html::encode($model['method']) ?></td>
        </tr>
        <tr>
            <td>应用</td>
            <td><?= Html::encode($model['app_id']) ?></td>
        </tr>
        <tr>
            <td>用户</td>
            <td><?= Yii::$app->services->backend->getUserName($model); ?></td>
        </tr>
        <tr>
            <td>模块</td>
            <td><?= Html::encode($model['module']) ?></td>
        </tr>
        <tr>
            <td>控制器方法</td>
            <td><?= Html::encode($model->controller); ?>/<?= Html::encode($model->action); ?></td>
        </tr>
        <tr>
            <td>Url</td>
            <td><?= Html::encode($model['url']) ?></td>
        </tr>
        <tr>
            <td>IP</td>
            <td><?= DebrisHelper::long2ip($model['ip']) ?></td>
        </tr>
        <tr>
            <td style="min-width: 100px">Get数据</td>
            <td style="max-width: 700px">
                <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['get_data'])) ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Post数据</td>
            <td style="max-width: 700px">
                <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['post_data'])) ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Header数据</td>
            <td style="max-width: 700px">
                <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['header_data'])) ?>
            </td>
        </tr>
        <tr>
            <td>状态码</td>
            <td>
                <?php if ($model->error_code < 300) { ?>
                    <span class="label label-primary"><?= $model->error_code; ?></span>
                <?php } else { ?>
                    <span class="label label-danger"><?= $model->error_code; ?></span>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td>状态信息</td>
            <td><?= $model['error_msg'] ?></td>
        </tr>
        <tr>
            <td style="min-width: 100px">状态说明</td>
            <td style="max-width: 700px">
                <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['error_data'])) ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>