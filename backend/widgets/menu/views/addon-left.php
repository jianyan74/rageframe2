<?php
use yii\helpers\Url;
use common\helpers\AddonUrl;
use common\enums\StatusEnum;
use \common\helpers\StringHelper;

$addonName = $addon['name'];
$addonName = StringHelper::toUnderScore($addonName);
?>

<div class="box box-solid rfAddonMenu">
    <div class="box-header with-border">
        <h3 class="rf-box-title">核心设置</h3>
    </div>
    <div class="box-body no-padding">
        <?php if($addon['is_setting'] == StatusEnum::ENABLED || $addon['is_cover'] == StatusEnum::ENABLED || $addon['is_rule'] == StatusEnum::ENABLED){ ?>
            <ul class="nav nav-pills nav-stacked">
                <?php if($addon['is_cover'] == StatusEnum::ENABLED){ ?>
                    <li>
                        <a href="<?= Url::to(['/addons/cover', 'addon' => $addonName])?>" title="应用入口">
                            <i class="fa fa-arrow-circle-right"></i>应用入口
                        </a>
                    </li>
                <?php } ?>
                <?php if($addon['is_rule'] == StatusEnum::ENABLED){ ?>
                    <li>
                        <a href="<?= Url::to(['/addons/rule', 'addon' => $addonName])?>" title="规则管理">
                            <i class="fa fa-gavel"></i>规则管理
                        </a>
                    </li>
                <?php } ?>
                <?php if($addon['is_setting'] == StatusEnum::ENABLED){ ?>
                    <li>
                        <a href="<?= AddonUrl::to(['setting/display', 'addon' => $addonName])?>" title="参数设置">
                            <i class="fa fa-cog"></i>参数设置
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
    <div class="box-header with-border">
        <h3 class="rf-box-title">业务菜单</h3>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <?php foreach ($menus as $vo){ ?>
                <li>
                    <a href="<?= AddonUrl::to([$vo['route']]); ?>" title="<?= $vo['title']; ?>">
                        <i class="<?= $vo['icon'] ? $vo['icon'] : 'fa fa-puzzle-piece'; ?>"></i><?= $vo['title']; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <div class="hr-line-dashed"></div>
    </div>
</div>