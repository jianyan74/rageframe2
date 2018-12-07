<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '第三方用户';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= $this->title; ?></h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <form action="" method="get" class="form-horizontal" role="form" id="form">
                                <div class="input-group m-b">
                                    <input type="text" class="form-control" name="keyword" placeholder="<?= $keyword ? $keyword : '请输入昵称'?>"/>
                                    <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>头像</th>
                            <th>昵称</th>
                            <th>性别</th>
                            <th>来源</th>
                            <th>绑定账号</th>
                            <th>生日</th>
                            <th>所在地区</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr id="<?= $model->id?>">
                                <td><?= $model->id?></td>
                                <td class="feed-element">
                                    <img src="<?= \common\helpers\HtmlHelper::headPortrait($model->head_portrait);?>" class="img-circle">
                                </td>
                                <td><?= $model->nickname?></td>
                                <td><?= $model->sex == 1 ? '男' : '女' ?></td>
                                <td><?= $model->oauth_client?></td>
                                <td>
                                    <?php if(!empty($model->member)){?>
                                        ID：<?= $model->member->id ?><br>
                                        昵称：<?= $model->member->nickname ?><br>
                                        账号：<?= $model->member->username ?><br>
                                        手机：<?= $model->member->mobile_phone ?>
                                    <?php }else{ ?>
                                        未绑定
                                    <?php } ?>
                                </td>
                                <td><?= $model->birthday?></td>
                                <td><?= $model->country?>·<?= $model->province?>·<?= $model->city?></td>
                                <td><?= Yii::$app->formatter->asDatetime($model->created_at)?></td>
                                <td>
                                    <a href="<?= Url::to(['edit','id' => $model->id])?>"><span class="btn btn-info btn-sm">编辑</span></a>
                                    <?= \common\helpers\HtmlHelper::statusSpan($model['status']); ?>
                                    <a href="<?= Url::to(['destroy','id' => $model->id])?>"  onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= LinkPager::widget([
                                'pagination' => $pages,
                                'maxButtonCount' => 5,
                                'firstPageLabel' => "首页",
                                'lastPageLabel' => "尾页",
                                'nextPageLabel' => "下一页",
                                'prevPageLabel' => "上一页",
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
