<?php
use yii\helpers\Url;
use common\enums\StatusEnum;
?>

<?php foreach($models as $item){ ?>
    <li class="treeview rfLeftMenu <?= ($item['cate']['is_default_show'] == StatusEnum::ENABLED || Yii::$app->params['isMobile'] == true) ? '' : 'hide'; ?> rfLeftMenu-<?= $item['cate_id']; ?>">
        <?php if(!empty($item['-'])){ ?>
            <a href="#">
                <i class="fa <?= $level == 1 ? $item['menu_css'] : 'fa-circle-o'; ?>"></i> <span><?= $item['title']; ?></span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <?= $this->render('menu-tree', [
                    'models' => $item['-'],
                    'level' => $level + 1,
                ])?>
            </ul>
        <?php }else{ ?>
            <a class="J_menuItem" href="<?= Url::toRoute($item['fullUrl']); ?>">
                <i class="fa <?= $level == 1 ? $item['menu_css'] : 'fa-circle-o'; ?>"></i>
                <span><?= $item['title']; ?></span>
            </a>
        <?php } ?>
    </li>
<?php } ?>