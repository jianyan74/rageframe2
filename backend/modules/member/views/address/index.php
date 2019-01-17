<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

$this->title = '收货地址';
$this->params['breadcrumbs'][] = ['label' => '会员信息', 'url' => ['member/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= HtmlHelper::create(['ajax-edit', 'member_id' => $member_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ])?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>真实姓名</th>
                        <th>手机号码</th>
                        <th>省市区</th>
                        <th>详细地址</th>
                        <th>是否默认</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id="<?= $model->id?>">
                            <td><?= $model->id?></td>
                            <td><?= Html::encode($model->realname) ?></td>
                            <td><?= $model->mobile ?></td>
                            <td><?= $model->address_name ?></td>
                            <td><?= $model->address_details ?></td>
                            <td><?= HtmlHelper::whether($model->is_default) ?></td>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at)?></td>
                            <td>
                                <?= HtmlHelper::edit(['ajax-edit', 'id' => $model->id, 'member_id' => $member_id], '编辑', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ])?>
                                <?= HtmlHelper::status($model['status']); ?>
                                <?= HtmlHelper::delete(['destroy','id' => $model->id, 'member_id' => $member_id])?>
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
