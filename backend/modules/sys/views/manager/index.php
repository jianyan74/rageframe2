<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\HtmlHelper;

$this->title = '后台用户';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= HtmlHelper::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="row normalPaddingJustV">
                    <div class="col-sm-3">
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['index']),
                            'method' => 'get'
                        ]); ?>
                        <div class="input-group m-b">
                            <?= Html::textInput('keyword', $keyword, [
                                'placeholder' => '请输入账号/姓名/手机号码',
                                'class' => 'form-control'
                            ])?>
                            <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>头像</th>
                        <th>登录账号</th>
                        <th>姓名</th>
                        <th>手机号码</th>
                        <th>角色</th>
                        <th>最后登陆</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id="<?= $model->id; ?>">
                            <td><?= $model->id; ?></td>
                            <td class="feed-element">
                                <img src="<?= \common\helpers\HtmlHelper::headPortrait($model->head_portrait);?>" class="img-circle rf-img-md img-bordered-sm">
                            </td>
                            <td><?= $model->username ?></td>
                            <td><?= $model->realname ?></td>
                            <td><?= $model->mobile ?></td>
                            <td>
                                <?php if ($model->id == Yii::$app->params['adminAccount']){ ?>
                                    <?= Html::tag('span', '超级管理员', ['class' => 'label label-success'])?>
                                <?php }else{ ?>
                                    <?= !empty($model->assignment->item_name)
                                        ? Html::tag('span', $model->assignment->item_name, ['class' => 'label label-primary'])
                                        : Html::tag('span', '未授权', ['class' => 'label label-default'])  ?>
                                <?php } ?>
                            </td>
                            <td>
                                最后访问IP：<?= $model->last_ip ?><br>
                                最后登录：<?= $model->last_time ? Yii::$app->formatter->asDatetime($model->last_time) : '暂无' ?><br>
                                访问次数：<?= $model->visit_count ?>
                            </td>
                            <td>
                                <?= HtmlHelper::linkButton(['ajax-edit','id' => $model->id], '账号密码', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]); ?>
                                <?= HtmlHelper::edit(['edit','id' => $model->id]); ?>
                                <?php if ($model->id != Yii::$app->params['adminAccount']){ ?>
                                    <?= HtmlHelper::status($model['status']); ?>
                                    <?= HtmlHelper::delete(['delete','id' => $model->id]); ?>
                                <?php } ?>
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