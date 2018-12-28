<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

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
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['index']),
                            'method' => 'get'
                        ]); ?>
                        <div class="col-sm-3">
                            <div class="input-group m-b">
                                <?= Html::textInput('keyword', $keyword, [
                                    'placeholder' => '请输入昵称',
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
                                    <img src="<?= HtmlHelper::headPortrait($model->head_portrait);?>" class="img-circle">
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
                                    <?= HtmlHelper::edit(['edit', 'id' => $model->id])?>
                                    <?= HtmlHelper::status($model['status']); ?>
                                    <?= HtmlHelper::delete(['destroy', 'id' => $model->id])?>
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
