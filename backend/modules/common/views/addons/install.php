<?php
use common\helpers\Url;
use common\helpers\AddonHelper;

$this->title = '安装插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index'])?>"> 已安装的插件</a></li>
                <li class="active"><a href="<?= Url::to(['install'])?>"> 安装插件</a></li>
                <li><a href="<?= Url::to(['create'])?>"> 设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>图标</th>
                            <th>模块名称</th>
                            <th>版本</th>
                            <th>作者</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($list as $key => $vo){ ?>
                            <tr>
                                <td class="feed-element" style="width: 70px">
                                    <img alt="image" class="img-rounded m-t-xs img-responsive" src="<?= AddonHelper::getAddonIcon($vo['name']); ?>" width="64" height="64">
                                </td>
                                <td>
                                    <h5><?= $vo['title'] ?></h5>
                                    <small>标识 : <?= $vo['name'] ?></small>
                                </td>
                                <td><?= $vo['version'] ?></td>
                                <td><?= $vo['author'] ?></td>
                                <td>
                                    <a href="<?= Url::to(['install','name' => $vo['name']])?>" data-method="post"><span class="btn btn-primary btn-sm">安装插件</span></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>