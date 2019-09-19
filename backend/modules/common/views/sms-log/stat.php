<?php

use common\helpers\Url;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">异常发送报表统计</h4>
</div>
<div class="modal-body">
    <?= \common\widgets\echarts\Echarts::widget([
        'config' => [
            'server' => Url::to(['stat']),
            'height' => '300px'
        ]
    ])?>
</div>