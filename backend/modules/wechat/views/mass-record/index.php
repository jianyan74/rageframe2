<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\wechat\MsgHistory;
use common\models\wechat\Rule;

$this->title = '定时群发';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>历史记录</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>发送对象</th>
                            <th>发送类别</th>
                            <th>粉丝数量</th>
                            <th>发送内容</th>
                            <th>发送状态</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr>
                                <td><?= $model->id; ?></td>
                                <td><?= $model->tag_name ?></td>
                                <td><?= $model->media_type?></td>
                                <td><?= $model->fans_num?></td>
                                <td><?= $model->content?></td>
                                <td>
                                    <?php if($model->send_status == \common\enums\StatusEnum::ENABLED){ ?>
                                        <span class="label label-info">已发送</span>
                                    <?php }else{ ?>
                                        <span class="label label-default">待发送</span>
                                    <?php } ?>
                                </td>
                                <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                                <td>
                                    <a href="<?= Url::to(['delete','id' => $model->id])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
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
