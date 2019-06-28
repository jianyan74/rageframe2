<?php if (!empty($menus)){ ?>
    <?= $this->render('menu-tree', [
        'menus' => $menus,
        'level' => 1,
    ])?>
<?php } ?>

<!--扩展插件模块信息-->
<?php foreach($addonsMenus as $key => $addon){ ?>
    <li class="treeview rfLeftMenu rfLeftMenuAddon hide">
        <a href="#">
            <i class="<?= Yii::$app->params['addonsGroup'][$key]['icon']; ?> rf-i"></i>
            <span><?= Yii::$app->params['addonsGroup'][$key]['title']; ?></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <?php foreach($addon as $value){?>
                <li>
                    <a class="J_menuItem" href="<?= $value['menuUrl']; ?>">
                        <i class="fa "></i>
                        <?= $value['title']; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>
