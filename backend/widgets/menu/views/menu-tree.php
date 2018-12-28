<?php
use yii\helpers\Url;
use common\enums\StatusEnum;

$paddingLeft = 32 + $level * 10;
?>

<?php foreach($models as $item){ ?>
    <li class="navbar-left-menu navbar-left-menu-<?= $item['cate_id']; ?> " style="display: <?= $item['cate']['is_default_show'] == StatusEnum::ENABLED ? 'block' : 'none';?>">
        <?php if(!empty($item['-'])){ ?>
            <a href="#" <?php if($level > 1){ ?> style="padding-left: <?= $paddingLeft;?>px" <?php }?>>
                <?php if ($level == 1){ ?>
                    <i class="fa <?= $item['menu_css']?>"></i>
                <?php } ?>
                <span class="nav-label"><?= $item['title']?></span>
                <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-second-level">
                <?= $this->render('menu-tree', [
                    'models' => $item['-'],
                    'level' => $level + 1,
                ])?>
            </ul>
        <?php }else{ ?>
            <a class="J_menuItem" href="<?= Url::toRoute($item['fullUrl'])?>" <?php if($level > 1){ ?> style="padding-left: <?= $paddingLeft;?>px" <?php }?>>
                <?php if ($level == 1){ ?>
                    <i class="fa <?= $item['menu_css'] ?? 'fa fa-magic'; ?>"></i>
                <?php } ?>
                <span class="nav-label"><?= $item['title']?></span>
            </a>
        <?php } ?>
    </li>
<?php } ?>