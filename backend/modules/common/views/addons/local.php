<?php

use common\helpers\Url;
use common\helpers\Html;
use common\helpers\AddonHelper;

$this->title = '安装插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 已安装的插件</a></li>
                <li class="active"><a href="<?= Url::to(['local']) ?>"> 安装插件</a></li>
                <li><a href="<?= Url::to(['create']) ?>"> 设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>图标</th>
                            <th>模块名称</th>
                            <th>作者</th>
                            <th>简介</th>
                            <th>版本</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($list as $key => $vo) { ?>
                            <tr>
                                <td class="feed-element" style="width: 70px">
                                    <img alt="image" class="img-rounded m-t-xs img-responsive" src="<?= AddonHelper::getAddonIcon($vo['name']); ?>" width="64" height="64">
                                </td>
                                <td>
                                    <h5><?= Html::encode($vo['title']) ?></h5>
                                    <small><?= Html::encode($vo['name']) ?></small>
                                </td>
                                <td><?= Html::encode($vo['author']) ?></td>
                                <td><?= Html::encode($vo['brief_introduction']) ?></td>
                                <td><?= Html::encode($vo['version']) ?></td>
                                <td>
                                    <a href="<?= Url::to(['install', 'name' => $vo['name']]) ?>"><span class="btn btn-primary btn-sm">安装插件</span></a>
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