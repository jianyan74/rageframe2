<?php
use yii\helpers\Url;
use common\helpers\AddonUrl;
use \common\helpers\StringHelper;

$this->title = '应用入口';
$this->params['breadcrumbs'][] = $this->title;

$addon = Yii::$app->params['addon']['name'];
$addon = StringHelper::toUnderScore($addon);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#tab-1" aria-expanded="true"> 微信入口</a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">前台入口</a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#tab-3" aria-expanded="false">api入口</a>
                </li>
            </ul>
            <div class="tab-content rf-auto">
                <div class="active tab-pane" id="tab-1">
                    <?php foreach (Yii::$app->params['addonBinding']['cover'] as $value){ ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="menu-title"><?= $value['title'] ?></label>
                                <input class="form-control" type="text" value="<?= AddonUrl::toWechat([$value['route'], 'addon' => $addon, 'route' => $value['route']]) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="menu-title">二维码</label><br>
                                <div class="row" style="padding-left: 15px">
                                    <img src="<?= Url::to(['qr-code', 'addon' => $addon,'url'=> AddonUrl::toWechat([$value['route'], 'addon' => $addon])])?>" class="rf-qr-p">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="tab-pane" id="tab-2">
                    <?php foreach (Yii::$app->params['addonBinding']['cover'] as $value){ ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="menu-title"><?= $value['title'] ?></label>
                                <input class="form-control" type="text" value="<?= AddonUrl::toFront([$value['route'], 'addon' => $addon, 'route' => $value['route']]) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="menu-title">二维码</label><br>
                                <div class="row" style="padding-left: 15px">
                                    <img src="<?= Url::to(['qr-code', 'addon' => $addon, 'route' => $value['route'],'url'=> AddonUrl::toFront([$value['route'], 'addon' => $addon])])?>" class="rf-qr-p">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="tab-pane" id="tab-3">
                    <?php foreach (Yii::$app->params['addonBinding']['cover'] as $value){ ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="menu-title"><?= $value['title'] ?></label>
                                <input class="form-control" type="text" value="<?= AddonUrl::toApi([$value['route'], 'addon' => $addon, 'route' => $value['route']]) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="menu-title">二维码</label><br>
                                <div class="row" style="padding-left: 15px">
                                    <img src="<?= Url::to(['qr-code', 'addon' => $addon, 'route' => $value['route'], 'url' => AddonUrl::toApi([$value['route'], 'addon' => $addon])])?>" class="rf-qr-p">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>