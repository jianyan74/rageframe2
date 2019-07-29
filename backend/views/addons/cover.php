<?php

use common\helpers\Html;
use common\helpers\StringHelper;

$this->title = '应用入口';
$this->params['breadcrumbs'][] = $this->title;

$addon = Yii::$app->params['addon']['name'];
$addon = StringHelper::toUnderScore($addon);

$active = '';
$i = 1;
?>

<!--jq二维码生成-->
<?= Html::jsFile('@web/resources/plugins/jquery-qrcode/jquery.qrcode.min.js'); ?>
<?= Html::jsFile('@web/resources/plugins/clipboard/clipboard.min.js'); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach ($covers as $key => $value) { ?>
                    <li class="<?= empty($active) ? 'active' : ''; ?>"><a data-toggle="tab" href="#tab-<?= $key ?>"
                                                                          aria-expanded="true"><?= Html::encode($key) ?></a></li>
                    <?php if (!$active) {
                        $active = $key;
                    } ?>
                <?php } ?>
            </ul>
            <div class="tab-content rf-auto">
                <?php foreach ($covers as $key => $value) { ?>
                    <?php $i++; ?>
                    <div class="<?= $active == $key ? 'active' : ''; ?> tab-pane" id="tab-<?= $key ?>">
                        <?php foreach ($value as $item) { ?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="menu-title"><?= Html::encode($item['title']); ?></label>
                                    <div class="input-group m-b">
                                        <input id="<?= $key . $i ?>" class="form-control" type="text" value="<?= $item['url'] ?>" readonly>
                                        <span class="input-group-btn">
                                            <button class="btn btn-white" data-clipboard-target="#<?= $key . $i ?>">复制链接</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="menu-title">二维码</label><br>
                                    <div class="row m-l-none">
                                        <div data-src="<?= $item['url']; ?>" class="rf-qr-p"></div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.rf-qr-p').each(function (letter, rows) {
            $(rows).qrcode({
                text: $(rows).data('src'),
                width: 150,
                height: 150,
            });
        })

        var clipboard = new ClipboardJS('.btn');

        clipboard.on('success', function(e) {
            console.info('Action:', e.action);
            console.info('Text:', e.text);
            console.info('Trigger:', e.trigger);

            e.clearSelection();
        });

        clipboard.on('error', function(e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
            rfMsg(e.trigger)
        });
    });
</script>