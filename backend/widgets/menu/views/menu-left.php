<?php
use yii\helpers\Url;
use \common\helpers\StringHelper;

?>

<?php if (!empty($models)){ ?>
    <?= $this->render('menu-tree', [
        'models' => $models,
        'level' => 1,
    ])?>
<?php } ?>

<!--扩展-->
<?php foreach($addonsMenu as $key => $addon){ ?>
    <li class="navbar-left-menu navbar-left-menu-addons" style="display: none">
        <a href="#">
            <i class="<?= Yii::$app->params['addonsGroup'][$key]['icon'] ?>"></i>
            <span class="nav-label"><?= Yii::$app->params['addonsGroup'][$key]['title'] ?></span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <?php foreach($addon as $value){?>
                <?php if(!empty($value['bindingMenu'])){ ?>
                    <li>
                        <a class="J_menuItem" href="<?= urldecode(Url::to(['addons/execute', 'addon' => StringHelper::toUnderScore($value['name']), 'route' => $value['bindingMenu']['route']])) ?>"><?= $value['title']?></a>
                    </li>
                <?php }else{ ?>
                    <li>
                        <a class="J_menuItem" href="<?= Url::to(['addons/blank', 'addon' => StringHelper::toUnderScore($value['name'])])?>"><?= $value['title']?></a>
                    </li>
                <?php } ?>
        <?php } ?>
        </ul>
    </li>
<?php } ?>


