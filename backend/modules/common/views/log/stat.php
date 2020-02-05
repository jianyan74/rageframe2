<?php

use common\helpers\Url;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">全局日志报表统计</h4>
</div>
<div class="modal-body">
    <?= \common\widgets\echarts\Echarts::widget([
        'config' => [
            'server' => Url::to(['stat']),
            'height' => '400px',
        ],
        'themeConfig' => [
            'today' => '今日',
            'yesterday' => '昨日',
            'thisWeek' => '本周',
            'thisMonth' => '本月',
            'thisYear' => '本年',
            'lastYear' => '去年',
        ],
    ]) ?>
</div>