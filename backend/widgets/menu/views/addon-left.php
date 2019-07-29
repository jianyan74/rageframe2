<?php

use common\helpers\Url;
use common\helpers\ArrayHelper;
use yii\helpers\Url as BaseUrl;
use common\enums\StatusEnum;
use \common\helpers\StringHelper;

/** @var array $addon */
$addonName = $addon['name'];
$addonName = StringHelper::toUnderScore($addonName);
?>

<div class="box box-solid p-xs rfAddonMenu">
    <?php if ($addon['is_setting'] == StatusEnum::ENABLED || $addon['is_cover'] == StatusEnum::ENABLED || $addon['is_rule'] == StatusEnum::ENABLED) { ?>
        <div class="box-header with-border">
            <h3 class="rf-box-title">核心设置</h3>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <?php if ($addon['is_cover'] == StatusEnum::ENABLED) { ?>
                    <li class="border-bottom-none">
                        <a href="<?= BaseUrl::to(['/addons/cover', 'addon' => $addonName]) ?>" title="应用入口">
                            <i class="fa fa-arrow-circle-right rf-i"></i>应用入口
                        </a>
                    </li>
                <?php } ?>
                <?php if ($addon['is_rule'] == StatusEnum::ENABLED) { ?>
                    <li>
                        <a href="<?= BaseUrl::to(['/addons/rule', 'addon' => $addonName]) ?>" title="规则回复">
                            <i class="fa fa-comments rf-i"></i>规则回复
                        </a>
                    </li>
                <?php } ?>
                <?php if ($addon['is_setting'] == StatusEnum::ENABLED) { ?>
                    <li>
                        <a href="<?= Url::to(['setting/display']) ?>" title="参数设置">
                            <i class="fa fa-cog rf-i"></i>参数设置
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
    <?php if (!empty($menus)) { ?>
        <div class="box-header with-border">
            <h3 class="rf-box-title">业务菜单</h3>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <?php foreach ($menus as $vo) { ?>
                    <li>
                        <a href="<?= Url::to(ArrayHelper::merge([$vo['route']], $vo['params'])); ?>"
                           title="<?= $vo['title']; ?>">
                            <i class="<?= $vo['icon'] ? $vo['icon'] : 'fa fa-puzzle-piece'; ?> rf-i"></i><?= $vo['title']; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="hr-line-dashed"></div>
        </div>
    <?php } ?>
</div>