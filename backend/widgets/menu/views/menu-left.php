<?php
use yii\helpers\Url;
use common\enums\StatusEnum;

?>

<?php foreach($models as $item){ ?>
    <li class="navbar-left-menu navbar-left-menu-<?= $item['cate_id']; ?> " style="display: <?= $item['cate']['is_default_show'] == StatusEnum::ENABLED ? 'block' : 'none';?>">
        <?php if(!empty($item['-'])){ ?>
            <a href="#">
                <i class="fa <?= $item['menu_css']?> fa-with"></i>
                <span class="nav-label"><?= $item['title']?></span>
                <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-second-level">
                <?php foreach($item['-'] as $list){ ?>
                    <li>
                        <?php if(!empty($list['-'])){ ?>
                            <a href="#"><?= $list['title']?> <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <?php foreach($list['-'] as $loop){ ?>
                                    <li><a class="J_menuItem" href="<?= Url::toRoute($loop['url'])?>"><?= $loop['title']?></a></li>
                                <?php } ?>
                            </ul>
                        <?php }else{ ?>
                            <a class="J_menuItem" href="<?= Url::toRoute($list['url'])?>"><?= $list['title']?></a>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        <?php }else{ ?>
            <a class="J_menuItem" href="<?= Url::toRoute($item['url'])?>">
                <i class="fa <?php if(!empty($item['menu_css'])){ ?><?= $item['menu_css']?><?php }else{ ?>fa fa-magic<?php } ?> fa-with"></i>
                <span class="nav-label"><?= $item['title']?></span>
            </a>
        <?php } ?>
    </li>
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
                        <a class="J_menuItem" href="<?= urldecode(Url::to(['addons/execute', 'addon' => $value['name'], 'route' => $value['bindingMenu']['route']])) ?>"><?= $value['title']?></a>
                    </li>
                <?php }else{ ?>
                    <li>
                        <a class="J_menuItem" href="<?= Url::to(['addons/blank', 'addon' => $value['name']])?>"><?= $value['title']?></a>
                    </li>
                <?php } ?>
        <?php } ?>
        </ul>
    </li>
<?php } ?>


