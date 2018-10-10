<?php
use yii\helpers\Url;
use common\helpers\AddonUrl;
use common\enums\StatusEnum;
?>

<div class="ibox-content">
    <div class="file-manager">
        <?php if($addon['is_setting'] == StatusEnum::ENABLED || !empty(Yii::$app->params['addonBinding']['cover']) || $addon['is_rule'] == StatusEnum::ENABLED){ ?>
            <h4>核心设置</h4>
            <ul class="folder-list p-xs">
                <?php if(!empty(Yii::$app->params['addonBinding']['cover'])){ ?>
                    <li>
                        <a href="<?= Url::to(['/addons/cover', 'addon' => Yii::$app->params['addon']['name']])?>" title="应用入口">
                            <i class="fa fa-arrow-circle-right"></i>应用入口
                        </a>
                    </li>
                <?php } ?>
                <?php if($addon['is_rule'] == StatusEnum::ENABLED){ ?>
                    <li>
                        <a href="<?= Url::to(['/addons/rule', 'addon' => Yii::$app->params['addon']['name']])?>" title="规则管理">
                            <i class="fa fa-gavel"></i>规则管理
                        </a>
                    </li>
                <?php } ?>
                <?php if($addon['is_setting'] == StatusEnum::ENABLED){ ?>
                    <li>
                        <a href="<?= AddonUrl::to(['setting/display', 'addon' => Yii::$app->params['addon']['name']])?>" title="参数设置">
                            <i class="fa fa-cog"></i>参数设置
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
        <h4>业务菜单</h4>
        <ul class="folder-list p-xs">
            <?php foreach (Yii::$app->params['addonBinding']['menu'] as $vo){ ?>
                <li>
                    <a href="<?= AddonUrl::to([$vo['route']])?>" title="<?= $vo['title'] ?>">
                        <i class="<?= $vo['icon'] ? $vo['icon'] : 'fa fa-puzzle-piece'; ?>"></i><?= $vo['title'] ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>