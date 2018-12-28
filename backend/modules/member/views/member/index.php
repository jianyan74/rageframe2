<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

$this->title = '会员信息';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= $this->title; ?></h5>
                    <div class="ibox-tools">
                        <?= HtmlHelper::create(['ajax-edit'], '新增', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                        ])?>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['index']),
                            'method' => 'get'
                        ]); ?>
                        <div class="col-sm-3">
                            <div class="input-group m-b">
                                <?= Html::textInput('keyword', $keyword, [
                                    'placeholder' => '请输入账号/姓名/手机号码/ID',
                                    'class' => 'form-control'
                                ])?>
                                <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>头像</th>
                            <th>登录账号</th>
                            <th>真实姓名</th>
                            <th>手机号码</th>
                            <th>邮箱</th>
                            <th>账户金额</th>
                            <th>最后登陆</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr id="<?= $model->id?>">
                                <td><?= $model->id?></td>
                                <td class="feed-element">
                                    <img src="<?= HtmlHelper::headPortrait(Html::encode($model->head_portrait));?>" class="img-circle">
                                </td>
                                <td><?= Html::encode($model->username) ?></td>
                                <td><?= Html::encode($model->realname) ?></td>
                                <td><?= $model->mobile_phone?></td>
                                <td><?= $model->email ?></td>
                                <td>
                                    余额：<?= $model->user_money?><br>
                                    积分：<?= $model->user_integral?><br>
                                    累计消费：<?= $model->accumulate_money?><br>
                                    累积金额：<?= $model->frozen_money?><br>
                                </td>
                                <td>
                                    最后访问IP：<?= $model->last_ip?><br>
                                    最后访问：<?= Yii::$app->formatter->asDatetime($model->last_time)?><br>
                                    访问次数：<?= $model->visit_count?><br>
                                    注册时间：<?= Yii::$app->formatter->asDatetime($model->created_at)?>
                                </td>
                                <td>
                                    <?= HtmlHelper::linkButton(['ajax-edit', 'id' => $model->id], '账号密码', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ])?>
                                    <?= HtmlHelper::linkButton(['address/index', 'member_id' => $model->id], '收货地址')?>
                                    <?= HtmlHelper::edit(['edit','id' => $model->id])?>
                                    <?= HtmlHelper::status($model['status']); ?>
                                    <?= HtmlHelper::delete(['destroy','id' => $model->id])?>
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
