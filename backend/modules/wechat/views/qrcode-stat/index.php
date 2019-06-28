<?php
use common\helpers\Url;
use common\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
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
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['/wechat/qrcode/index'])?>"> 二维码管理</a></li>
                <li class="active"><a href="<?= Url::to(['index'])?>"> 扫描统计</a></li>
                <li><a href="<?= Url::to(['/wechat/qrcode/long-url'])?>"> 长链接转二维码</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="row farPaddingJustV">
                        <div class="col-sm-8">
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['index']),
                                'method' => 'get'
                            ]); ?>
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
                                    <?= Html::textInput('keyword', $keyword, [
                                        'placeholder' => '场景名称',
                                        'class' => 'form-control'
                                    ])?>
                                    <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                        <div class="col-sm-4">
                            <div class="pull-right">
                                关注扫描 <strong class="text-danger"><?= $attention_count ?></strong> 次 ;
                                已关注扫描 <strong class="text-danger"><?= $scan_count ?></strong> 次 ;
                                总计 <strong class="text-danger"><?= $pages->totalCount ?></strong> 次 ;
                                <?= Html::a('导出Excel', ['export','from_date' => $from_date,'to_date' => $to_date,'type' => $type,'keyword' => $keyword])?>
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
                                    <td><?= Html::delete(['delete','id' => $model->id]); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12">
                                <?= LinkPager::widget([
                                    'pagination' => $pages,
                                    'maxButtonCount' => 5,
                                ]);?>
                            </div>
                        </div>
                    </div>
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
