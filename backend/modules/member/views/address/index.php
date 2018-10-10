<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\common\Provinces;

$this->title = '收货地址';
$this->params['breadcrumbs'][] = ['label' => '会员信息', 'url' => ['member/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>收货地址</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>用户昵称</th>
                            <th>真实姓名</th>
                            <th>手机号码</th>
                            <th>省</th>
                            <th>市</th>
                            <th>区</th>
                            <th>详细</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr id="<?= $model->id?>">
                                <td><?= $model->id?></td>
                                <td><?= $model->member->nickname ?></td>
                                <td><?= $model->realname ?></td>
                                <td><?= $model->mobile ?></td>
                                <td><?= Provinces::getCityName($model->provinces) ?></td>
                                <td><?= Provinces::getCityName($model->city) ?></td>
                                <td><?= Provinces::getCityName($model->area) ?></td>
                                <td><?= $model->detailed_address?></td>
                                <td><?= Yii::$app->formatter->asDatetime($model->created_at)?></td>
                                <td>
                                    <a href="<?= Url::to(['ajax-edit','id' => $model->id, 'member_id' => $member_id])?>" data-toggle='modal' data-target='#ajaxModal'><span class="btn btn-info btn-sm">编辑</span></a>
                                    <?= \common\helpers\HtmlHelper::statusSpan($model['status']); ?>
                                    <a href="<?= Url::to(['delete','id' => $model->id, 'member_id' => $member_id])?>"  onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
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
