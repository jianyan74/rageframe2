<?php
use common\helpers\Url;
use yii\widgets\LinkPager;
use common\models\wechat\Qrcode;
use common\helpers\Html;

$this->title = '二维码管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['index'])?>"> 二维码管理</a></li>
                <li><a href="<?= Url::to(['/wechat/qrcode-stat/index'])?>"> 扫描统计</a></li>
                <li><a href="<?= Url::to(['long-url'])?>"> 长链接转二维码</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ])?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>二维码</th>
                            <th>场景名称</th>
                            <th>对应关键字</th>
                            <th>场景ID/场景字符串</th>
                            <th>有效期</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr>
                                <td>
                                    <a href="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?= $model->ticket ?>" data-fancybox="gallery">
                                        <img src="<?= Url::to(['qr', 'shortUrl' => Yii::$app->request->hostInfo])?>" alt="" width="45">
                                    </a>
                                </td>
                                <td><a href="<?= Url::to(['qrcode-stat/index','keyword' =>  $model->name])?>"><?= $model->name ?></a></td>
                                <td><?= $model->keyword ?></td>
                                <td><?= $model->model == Qrcode::MODEL_TEM ? $model->scene_id : $model->scene_str ;?></td>
                                <td>
                                    开始时间 : <?= Yii::$app->formatter->asDatetime($model->created_at) ?><br>
                                    结束时间 : <?php if($model->model == Qrcode::MODEL_TEM){ ?>
                                        <?= Yii::$app->formatter->asDatetime($model->created_at + $model->expire_seconds) ?>
                                    <?php }else{ ?>
                                        <font color="green">永不</font>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($model->model == Qrcode::MODEL_PERPETUAL){ ?>
                                        <span class='label label-primary'>永不</span>
                                    <?php }else{ ?>
                                        <?= $model->end_time < time() ? "<span class='label label-danger'>已过期</span>" : "<span class='label label-primary'>未过期</span>" ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?= Html::linkButton(['down', 'id' => $model->id], '下载'); ?>
                                    <?= Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ])?>
                                    <?php if($model->model == Qrcode::MODEL_PERPETUAL){ ?>
                                        <?= Html::delete(['delete', 'id' => $model->id]); ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="11" style="line-height: 16px">
                                <?= Html::linkButton(['delete-all'], '删除过期二维码', [
                                    'class' => 'btn btn-warning btn-sm'
                                ]); ?>
                                <span>注意：永久二维码无法在微信平台删除，但是您可以点击
                                     <a href="javascript:;" class="color-default">【删除】</a>来删除本地数据。
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= LinkPager::widget([
                                'pagination' => $pages,
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>