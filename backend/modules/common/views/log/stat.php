<?php

use common\helpers\Url;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">异常请求报表统计</h4>
</div>
<div class="modal-body">
    <?= \backend\widgets\echarts\Echarts::widget([
        'config' => [
            'server' => Url::to(['stat']),
            'height' => '400px'
        ]
    ])?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>