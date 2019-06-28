<?php
use common\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\Html;
use common\enums\StatusEnum;
use common\helpers\Auth;

$this->title = '定时群发';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <!-- 权限校验判断 -->
                    <?= Html::create(['edit']);?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>发送对象</th>
                        <th>发送类别</th>
                        <th>粉丝数量</th>
                        <th>发送时间</th>
                        <th>发送状态</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr>
                            <td><?= $model->id; ?></td>
                            <td><?= $model->tag_name ?></td>
                            <td><span class="label label-info"><?= $model->module ?></span></td>
                            <td><?= $model->fans_num?></td>
                            <td>
                                预计：<?= Yii::$app->formatter->asDatetime($model->send_time) ?><br>
                                实际：<?= !empty($model->final_send_time) ? Yii::$app->formatter->asDatetime($model->final_send_time) : '暂无'  ?>
                            </td>
                            <td>
                                <?php if($model->send_status == StatusEnum::ENABLED){ ?>
                                    <span class="label label-info">已发送</span>
                                <?php }else if ($model->send_status == StatusEnum::DELETE){ ?>
                                    <span class="label label-danger">发送失败</span>
                                <?php }else{ ?>
                                    <span class="label label-default">待发送</span>
                                <?php } ?>
                            </td>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                            <td>
                                <?= Html::edit(['edit','id' => $model->id]);?>
                                <?= Html::delete(['delete','id' => $model->id]);?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <?= LinkPager::widget([
                    'pagination' => $pages
                ]);?>
            </div>
        </div>
    </div>
</div>
