<?php

use common\helpers\Url;

?>

<li class="dropdown notifications-menu rf-notif">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"><?= $notifyPage->totalCount ?? 0; ?></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">你有 <?= $notifyPage->totalCount ?? 0; ?> 条未读消息</li>
        <li class="body">
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
                <?php foreach ($notify as $item) { ?>
                    <li>
                        <a href="javascript:void (0);">
                            收到一条<?= $item['type']; ?>消息
                            <small>
                                <i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativeTime($item['notify']['created_at']) ?>
                            </small>
                        </a>
                    </li>
                <?php } ?>
                <?php if (empty($notify)) { ?>
                    <li class="text-center">
                        <a href="javascript:void (0);" style="color: #ccc">
                            暂无消息...
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>
        <!-- 验证权限 -->
        <li class="footer"><a href="<?= Url::to(['/notify/remind']); ?>" class="J_menuItem" onclick="$('body').click();">查看消息</a></li>
    </ul>
</li>