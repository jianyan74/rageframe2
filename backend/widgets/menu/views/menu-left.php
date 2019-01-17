<?php if (!empty($models)){ ?>
    <?= $this->render('menu-tree', [
        'models' => $models,
        'level' => 1,
    ])?>
<?php } ?>

<!--扩展插件模块信息-->
<?php foreach($addonsInfo as $key => $addon){ ?>
    <li class="treeview rfLeftMenu rfLeftMenu-addons <?php if(Yii::$app->params['isMobile'] == false) echo 'hide'; ?>">
        <a href="#">
            <i class="<?= Yii::$app->params['addonsGroup'][$key]['icon']; ?>"></i>
            <span><?= Yii::$app->params['addonsGroup'][$key]['title']; ?></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <?php foreach($addon as $value){?>
                <li>
                    <a class="J_menuItem" href="<?= $value['menuUrl']; ?>">
                        <i class="fa fa-circle-o"></i>
                        <?= $value['title']; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>