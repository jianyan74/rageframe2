<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\HtmlHelper;
use common\enums\StatusEnum;
use backend\widgets\menu\MenuLeftWidget;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <!-- Meta -->
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"<
        <meta name="renderer" content="webkit">
        <?= Html::csrfMetaTags() ?>
        <title><?= Yii::$app->params['adminTitle'];?></title>
        <?php $this->head() ?>
    </head>

    <body class="fixed-sidebar full-height-layout gray-bg skin-<?= $style->skin_id; ?>">
    <?php $this->beginBody() ?>
    <div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i></div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element text-center">
                            <span><img class="img-circle" src="<?= HtmlHelper::headPortrait(Yii::$app->user->identity->head_portrait);?>" width="48" height="48" id="head_portrait" onerror="this.src='<?= HtmlHelper::onErrorImgBase64();?>'"/></span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs"><strong class="font-bold"><?= Yii::$app->user->identity->username; ?></strong></span>
                                <span class="text-muted text-xs block">
                                    <?php if (Yii::$app->user->id == Yii::$app->params['adminAccount']){; ?>
                                        超级管理员
                                    <?php }else{ ?>
                                        <?= isset(Yii::$app->user->identity->assignment->item_name) ? Yii::$app->user->identity->assignment->item_name : '未授权'?>
                                    <?php } ?>
                                    <b class="caret"></b>
                                </span>
                            </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a class="J_menuItem" href="<?= Url::to(['/sys/manager/personal'])?>"  onclick="$('body').click();">个人中心</a></li>
                                <li><a class="J_menuItem" href="<?= Url::to(['/sys/manager/up-password'])?>"  onclick="$('body').click();">修改密码</a></li>
                                <li><a class="J_menuItem" href="<?= Url::to(['/main/clear-cache'])?>"  onclick="$('body').click();">清除缓存</a></li>
                                <li class="divider"></li>
                                <li><a href="<?= Url::to(['/site/logout'])?>" data-method="post">安全退出</a></li>
                            </ul>
                        </div>
                        <div class="logo-element"><?= Yii::$app->params['adminAcronym']; ?>
                        </div>
                    </li>
                    <?= MenuLeftWidget::widget() ?>
                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">

                    <ul class="nav navbar-top-links navbar-left">
                        <li class="dropdown">
                            <a class="navbar-minimalize" href="#">
                                <i class="fa fa-bars"></i>
                            </a>
                        </li>
                        <?php foreach ($menuCates as $cate){ ?>
                            <?php if ($cate['status'] == StatusEnum::ENABLED) { ?>
                                <li class="dropdown">
                                    <a class="navbar-top-menu <?= $cate['is_default_show'] == StatusEnum::ENABLED ? 'navbar-top-menu-hover' : '' ?>" href="#" menu-type="<?= $cate['id']; ?>">
                                        <i class="fa <?= $cate['icon']; ?>"></i><?= $cate['title']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if (Yii::$app->debris->config('sys_addon_show') == StatusEnum::ENABLED) { ?>
                            <li class="dropdown">
                                <a class="navbar-top-menu" href="#" menu-type="addons">
                                    <i class="fa fa-th-large"></i>应用中心
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown hidden-xs">
                            <a class="right-sidebar-toggle" aria-expanded="false">
                                <i class="fa fa-tasks"></i>主题
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row content-tabs">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-angle-double-left"></i></button>
                <nav class="page-tabs J_menuTabs" id="rftags">
                    <div class="page-tabs-content">
                        <a href="javascript:;" class="active J_menuTab" data-id="<?= Url::to(['main/system']); ?>" id="rftagsIndexLink">首页</a>
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
                <a href="<?= Url::to(['site/logout'])?>" data-method="post" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" id="content-main">
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::to(['main/system']); ?>" frameborder="0" data-id="<?= Url::to(['main/system']); ?>" seamless></iframe>
                <!--默认主页需在对应的页面显示iframe元素上添加name="iframe0"和data-id="默认主页的url"-->
            </div>
            <div class="footer">
                <div class="pull-left">当前版本：<?= Yii::$app->params['exploitVersions']; ?></div>
                <div class="pull-right"><?= Yii::$app->debris->config('web_copyright'); ?></div>
            </div>
        </div>
        <!--右侧部分结束-->
        <div id="right-sidebar">
            <div class="sidebar-container">
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="skin-setttings">
                            <div class="title">主题设置</div>
                            <div class="setings-item">
                                <span>Tag 页签</span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="switchtags" class="onoffswitch-checkbox" id="switchtags">
                                        <label class="onoffswitch-label" for="switchtags">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                                <span>收起左侧菜单</span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                                        <label class="onoffswitch-label" for="collapsemenu">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                                <span>固定顶部</span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                                        <label class="onoffswitch-label" for="fixednavbar">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                                <span>固定宽度</span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                                        <label class="onoffswitch-label" for="boxedlayout">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="title">皮肤选择</div>
                            <div class="setings-item default-skin nb">
                        <span class="skin-name ">
                             <a href="#" class="s-skin-0">
                                 默认皮肤
                             </a>
                        </span>
                            </div>
                            <div class="setings-item blue-skin nb">
                        <span class="skin-name ">
                            <a href="#" class="s-skin-1">
                                蓝色主题
                            </a>
                        </span>
                            </div>
                            <div class="setings-item yellow-skin nb">
                        <span class="skin-name ">
                            <a href="#" class="s-skin-3">
                                黄色/紫色主题
                            </a>
                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--js配置-->
    <script>
        var styleUrl = "<?= Url::to(['sys/style/update']); ?>";
    </script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>