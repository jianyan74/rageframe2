<?php

use common\helpers\Html;

?>

<div class="box-body" id="<?= $boxId; ?>">
    <div>
        <?php $i = 0; ?>
        <?php foreach ($themeConfig as $key => $value) { ?>
            <span class="<?= $i == 0 ? 'orange' : '' ?> pointer"
                  data-type="<?= Html::encode($key) ?>"> <?= Html::encode($value) ?></span>
            <?php $i++; ?>
        <?php } ?>
    </div>
    <div style="height: <?= $config['height'] ?>" id="<?= $boxId; ?>-echarts"></div>
    <!-- /.row -->
</div>