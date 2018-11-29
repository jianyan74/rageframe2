<?php
use yii\helpers\Url;
?>

<ul class="nav nav-tabs">
    <li <?php if ($type == 'action'){ ?>class="active"<?php } ?>><a href="<?= Url::to(['action'])?>"> 行为日志</a></li>
    <li <?php if ($type == 'error'){ ?>class="active"<?php } ?>><a href="<?= Url::to(['error'])?>"> 报错日志</a></li>
    <li <?php if ($type == 'pay'){ ?>class="active"<?php } ?>><a href="<?= Url::to(['pay'])?>"> 支付日志</a></li>
</ul>