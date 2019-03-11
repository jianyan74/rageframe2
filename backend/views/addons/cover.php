<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\AddonUrl;
use \common\helpers\StringHelper;

$this->title = '应用入口';
$this->params['breadcrumbs'][] = $this->title;

$addon = Yii::$app->params['addon']['name'];
$addon = StringHelper::toUnderScore($addon);
?>

<!--jq二维码生成-->
<?= Html::jsFile('@web/resources/plugins/jquery-qrcode/jquery.qrcode.min.js'); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">微信入口</a></li>
                <li><a data-toggle="tab" href="#tab-2" aria-expanded="false">前台入口</a></li>
                <li><a data-toggle="tab" href="#tab-3" aria-expanded="false">api入口</a></li>
            </ul>
            <div class="tab-content rf-auto">
                <div class="active tab-pane" id="tab-1">
                    <?php foreach (Yii::$app->params['addonBinding']['cover'] as $value){ ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="menu-title"><?= $value['title']; ?></label>
                                <input class="form-control" type="text" value="<?= AddonUrl::toWechat([$value['route'], 'addon' => $addon, 'route' => $value['route']]) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="menu-title">二维码</label><br>
                                <div class="row m-l-none">
                                    <div data-src="<?= AddonUrl::toWechat([$value['route'], 'addon' => $addon]); ?>" class="rf-qr-p"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="tab-pane" id="tab-2">
                    <?php foreach (Yii::$app->params['addonBinding']['cover'] as $value){ ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="menu-title"><?= $value['title']; ?></label>
                                <input class="form-control" type="text" value="<?= AddonUrl::toFront([$value['route'], 'addon' => $addon, 'route' => $value['route']]) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="menu-title">二维码</label><br>
                                <div class="row m-l-none">
                                    <div data-src="<?= AddonUrl::toFront([$value['route'], 'addon' => $addon]); ?>" class="rf-qr-p"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="tab-pane" id="tab-3">
                    <?php foreach (Yii::$app->params['addonBinding']['cover'] as $value){ ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="menu-title"><?= $value['title']; ?></label>
                                <input class="form-control" type="text" value="<?= AddonUrl::toApi([$value['route'], 'addon' => $addon, 'route' => $value['route']]) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="menu-title">二维码</label><br>
                                <div class="row m-l-none">
                                    <div data-src="<?= AddonUrl::toApi([$value['route'], 'addon' => $addon]); ?>" class="rf-qr-p"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.rf-qr-p').each(function (letter, rows) {
            $(rows).qrcode({
                text: $(rows).data('src'),
                width: 150,
                height: 150,
            });
        })
    });
</script>