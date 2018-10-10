<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\daterange\DateRangePicker;
use common\models\wechat\QrcodeStat;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = '扫描统计';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li><a href="<?= Url::to(['/wechat/qrcode/index'])?>"> 二维码管理</a></li>
                    <li class="active"><a href="<?= Url::to(['index'])?>"> 扫描统计</a></li>
                    <li><a href="<?= Url::to(['/wechat/qrcode/long-url'])?>"> 长链接转二维码</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    <form action="" method="get" class="form-horizontal" role="form" id="form">
                                        <div class="col-sm-4">
                                            <div class="input-group drp-container">
                                                <?= DateRangePicker::widget([
                                                        'name' => 'queryDate',
                                                        'value' => $from_date . '-' . $to_date,
                                                        'readonly' => 'readonly',
                                                        'useWithAddon' => true,
                                                        'convertFormat' => true,
                                                        'startAttribute' => 'from_date',
                                                        'endAttribute' => 'to_date',
                                                        'startInputOptions' => ['value' => $from_date],
                                                        'endInputOptions' => ['value' => $to_date],
                                                        'pluginOptions' => [
                                                            'locale' => ['format' => 'Y-m-d'],
                                                        ]
                                                    ]) . $addon;?>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <?= Html::dropDownList('type', $type, ArrayHelper::merge(['' => '全部'], QrcodeStat::$typeExplain), ['class'=>'form-control']);?>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group m-b">
                                                <input type="text" class="form-control" name="keyword" placeholder="<?= $keyword ? $keyword : '场景名称'?>"/>
                                                <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-4">
                                    <div class="ibox-tools">
                                        关注扫描 <strong class="text-danger"><?= $attention_count ?></strong> 次 ;
                                        已关注扫描 <strong class="text-danger"><?= $scan_count ?></strong> 次 ;
                                        总计 <strong class="text-danger"><?= $pages->totalCount ?></strong> 次 ;
                                        <a href="<?= Url::to(['export','from_date' => $from_date,'to_date' => $to_date,'type' => $type,'keyword' => $keyword]);?>">导出Excel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>昵称</th>
                                        <th>场景名称</th>
                                        <th>场景ID/场景值</th>
                                        <th>关注扫描</th>
                                        <th>当前关注状态</th>
                                        <th>openid</th>
                                        <th>扫描时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($models as $model){ ?>
                                        <tr>
                                            <td><?= $model->id ?></td>
                                            <td><?= isset($model->fans->nickname) ? $model->fans->nickname : ''; ?></td>
                                            <td><?= $model->name ?></td>
                                            <td><?= $model->scene_id ? $model->scene_id : $model->scene_str ;?></td>
                                            <td><?= QrcodeStat::$typeExplain[$model->type]; ?></td>
                                            <td><?= isset($model->fans->follow) && $model->fans->follow == 1 ? '已关注': '取消关注'; ?></td>
                                            <td><?= $model->openid ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                                            <td>
                                                <a href="<?= Url::to(['delete','id'=>$model->id])?>"><span class="btn btn-warning btn-sm">删除</span></a>
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
        </div>
    </div>
</div>
