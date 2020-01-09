<?php
use common\helpers\ImageHelper;
use common\helpers\Url;
use common\enums\StatusEnum;
use backend\widgets\notify\Notify;
?>

<header class="main-header">
    <!-- Logo区域 -->
    <a href="<?= Yii::$app->homeUrl; ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><?= Yii::$app->params['adminAcronym']; ?></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><?= Yii::$app->params['adminTitle']; ?></span>
    </a>
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="navbar-custom-menu pull-left">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu rfTopMenu">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">切换导航</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
                <?php foreach ($menuCates as $cate){ ?>
                    <li class="dropdown notifications-menu rfTopMenu hide <?php if($cate['is_default_show'] == StatusEnum::ENABLED) echo 'rfTopMenuHover'; ?>" data-type="<?= $cate['id']; ?>" data-addon_centre="<?= $cate['addon_centre']; ?>">
                        <a class="dropdown-toggle">
                            <i class="fa <?= $cate['icon']; ?>"></i> <?= $cate['title']; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="navbar-custom-menu top-right">
            <ul class="nav navbar-nav">
                <!-- 自动隐藏菜单 -->
                <li class="dropdown tasks-menu hide-menu hide">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        <i class="icon ion-navicon-round"></i>
                        <i class="icon ion-arrow-down-b"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu"></ul>
                        </li>
                    </ul>
                </li>
                <?= Notify::widget(); ?>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img class="user-image head_portrait" src="<?= ImageHelper::defaultHeaderPortrait($manager->head_portrait); ?>"/>
                        <span class="hidden-xs"><?= $manager->username; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img class="img-circle head_portrait" src="<?= ImageHelper::defaultHeaderPortrait($manager->head_portrait); ?>"/>
                            <p>
                                <?= $manager->username; ?>
                                <small><?= Yii::$app->request->userIP; ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="<?= Url::to(['/base/member/personal']); ?>" class="J_menuItem" onclick="$('body').click();">个人信息</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="<?= Url::to(['/base/member/up-password']); ?>" class="J_menuItem" onclick="$('body').click();">修改密码</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="<?= Url::to(['/main/clear-cache']); ?>" class="J_menuItem" onclick="$('body').click();">清理缓存</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
                <li id="logout" class="hide">
                    <a href="<?= Url::to(['site/logout']); ?>" data-method="post"><i class="fa fa fa-sign-out"></i>退出</a>
                </li>
            </ul>
        </div>
    </nav>
</header>