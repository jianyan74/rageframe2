<?php

use common\helpers\Html;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table">
        <tbody>
        <tr>
            <td style="min-width: 100px">具体信息</td>
            <td style="max-width: 700px">
                <?php $model['error_data'] ? Yii::$app->debris->p(Html::encode($model['error_data'])) : '' ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>