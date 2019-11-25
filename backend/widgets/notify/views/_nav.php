<?php

use common\helpers\Url;
use backend\widgets\notify\Notify;

?>

<ul class="nav nav-pills nav-stacked">
    <li>
        <a href="<?= Url::to(['remind']); ?>" title="提醒列表"><i class="fa fa-bell"></i> 提醒列表</a>
        <a href="<?= Url::to(['message']); ?>" title="私信列表"><i class="fa fa-envelope"></i> 私信列表</a>
        <a href="<?= Url::to(['announce']); ?>" title="公告列表"><i class="fa fa-commenting"></i> 公告列表</a>
    </li>
</ul>

<div id="notify" class="hidden">
    <?= Notify::widget(); ?>
</div>

<script>
    $(document).ready(function () {
        var obj = $('.rf-notif', window.parent.document);
        var html = $('#notify .rf-notif').html();

        var warning = $(html).find('.label-warning').html();
        var header = $(html).find('.header').html();
        var body = $(html).find('.body').html();

        $(obj).find('.header').html(header);
        $(obj).find('.body').html(body);
        $(obj).find('.label-warning').html(warning);
    })
</script>
