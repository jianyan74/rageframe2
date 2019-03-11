<?php
use yii\helpers\Url;
use common\helpers\HtmlHelper;
use common\enums\StatusEnum;
use common\helpers\AuthHelper;
use backend\widgets\menu\MenuLeftWidget;
use backend\assets\MainAsset;

MainAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"<
        <meta name="renderer" content="webkit">
        <?= HtmlHelper::csrfMetaTags() ?>
        <title><?= Yii::$app->params['adminTitle'];?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini fixed">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <!-- 头部区域 -->
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
                            <?php if ($cate['status'] == StatusEnum::ENABLED && AuthHelper::verify('cate:' . $cate['id'])) { ?>
                                <li class="dropdown notifications-menu rfTopMenu hide <?php if($cate['is_default_show'] == StatusEnum::ENABLED) echo 'rfTopMenuHover'; ?>" data-type="<?= $cate['id']; ?>">
                                    <a class="dropdown-toggle">
                                        <i class="fa <?= $cate['icon']; ?>"></i> <?= $cate['title']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if (Yii::$app->debris->config('sys_addon_show') == StatusEnum::ENABLED && AuthHelper::verify('addons')) { ?>
                            <li class="dropdown notifications-menu rfTopMenu hide" data-type="addons">
                                <a class="dropdown-toggle">
                                    <i class="fa fa-th-large"></i> 应用中心
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <?php if (AuthHelper::verify('base-announce')){ ?>
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="label label-warning"><?= $notifyPage->totalCount ?? 0; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">你有 <?= $notifyPage->totalCount ?? 0; ?> 条未读消息</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            <?php foreach ($notify as $item){ ?>
                                                <li>
                                                    <a href="javascript:void (0);">
                                                        收到一条<?= $item['type']; ?>消息
                                                        <small><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativeTime($item['notify']['created_at'])?></small>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <!-- 验证权限 -->
                                    <li class="footer"><a href="<?= Url::to(['/sys/manager-notify/announce']); ?>" class="J_menuItem" onclick="$('body').click();">查看消息</a></li>
                                </ul>
                            </li>
                        <?php } ?>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img class="user-image head_portrait" src="<?= HtmlHelper::headPortrait($manager->head_portrait);?>"/>
                                <span class="hidden-xs"><?= $manager->username; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img class="img-circle head_portrait" src="<?= HtmlHelper::headPortrait($manager->head_portrait);?>"/>
                                    <p>
                                        <?= $manager->username; ?>
                                        <small><?= Yii::$app->request->userIP; ?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 text-center <?php if(!AuthHelper::verify('/sys/manager/personal')) echo 'hide'; ?>">
                                            <a href="<?= Url::to(['/sys/manager/personal']); ?>" class="J_menuItem" onclick="$('body').click();">个人信息</a>
                                        </div>
                                        <div class="col-xs-4 text-center <?php if(!AuthHelper::verify('/sys/manager/up-password')) echo 'hide'; ?>">
                                            <a href="<?= Url::to(['/sys/manager/up-password']); ?>" class="J_menuItem" onclick="$('body').click();">修改密码</a>
                                        </div>
                                        <div class="col-xs-4 text-center <?php if(!AuthHelper::verify('/main/clear-cache')) echo 'hide'; ?>">
                                            <a href="<?= Url::to(['/main/clear-cache']); ?>" class="J_menuItem" onclick="$('body').click();">清空缓存</a>
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
        <!-- 左侧菜单栏 -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img class="img-circle head_portrait" src="<?= HtmlHelper::headPortrait($manager->head_portrait);?>"/>
                    </div>
                    <div class="pull-left info">
                        <p><?= $manager->username; ?></p>
                        <a href="#">
                            <i class="fa fa-circle text-success"></i>
                            <?php if (Yii::$app->services->sys->isAuperAdmin()){; ?>
                                超级管理员
                            <?php }else{ ?>
                                <?= Yii::$app->services->sys->auth->getRole()->name ?? '游客'?>
                            <?php } ?>
                        </a>
                    </div>
                </div>
                <!-- 侧边菜单 -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header" data-rel="external">系统菜单</li>
                    <?= MenuLeftWidget::widget(); ?>
                    <li class="header" data-rel="external">相关链接</li>
                    <li><a href="http://www.rageframe.com" target="_blank"><i class="fa fa-bookmark text-red"></i> <span>系统官网</span></a></li>
                    <li><a href="https://github.com/jianyan74/rageframe2/blob/master/docs/guide-zh-CN/README.md" target="_blank"><i class="fa fa-list text-yellow"></i> <span>在线文档</span></a></li>
                    <li><a href="https://jq.qq.com/?_wv=1027&k=5yvRLd7" target="_blank"><i class="fa fa-qq text-aqua"></i> <span>QQ交流群</span></a></li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
        <!-- 主体内容区域 -->
        <div class="content-wrapper" style="overflow: hidden; width: auto; height: 567px;">
            <div class="content-tabs hide">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-angle-double-left"></i></button>
                <nav class="page-tabs J_menuTabs" id="rftags">
                    <div class="page-tabs-content">
                        <a href="javascript:void (0);" class="active J_menuTab" data-id="<?= Url::to(['/main/system']); ?>" id="rftagsIndexLink">首页</a>
                        <!--默认主页需在对应的选项卡a元素上添加data-id="默认主页的url"-->
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight"><i class="fa fa-angle-double-right"></i></button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作</button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a></li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a></li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a></li>
                    </ul>
                </div>
                <a href="<?= Url::to(['site/logout']); ?>" data-method="post" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="J_mainContent" id="content-main">
                <!--默认主页需在对应的页面显示iframe元素上添加name="iframe0"和data-id="默认主页的url"-->
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::to(['main/system']); ?>" frameborder="0" data-id="<?= Url::to(['main/system']); ?>" seamless></iframe>
            </div>
        </div>
        <!-- 底部区域 -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <?= Yii::$app->debris->config('web_copyright'); ?>
            </div>
            当前版本：<?= Yii::$app->version; ?>
        </footer>
        <!-- 布局样式设置 -->
        <aside class="control-sidebar control-sidebar-dark">
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab"></div>
            </div>
        </aside>
        <!-- 右侧区域 -->
        <div class="control-sidebar-bg"></div>
    </div>
    <script>
        // 配置
        var config = {
            tag: <?= Yii::$app->debris->config('sys_tags') ?? false; ?>,
            isMobile: "<?= Yii::$app->params['isMobile'] ?? false; ?>",
        };

        if (config.isMobile == false){
            $('.rfTopMenu').removeClass('hide');
        }

        if (config.tag == false){
            $('.logout').removeClass('hide');
        }else{
            $('.content-tabs').removeClass('hide');
        }
    </script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>