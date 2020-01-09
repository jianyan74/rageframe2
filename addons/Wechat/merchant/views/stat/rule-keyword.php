<?php
use common\helpers\Html;
use common\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use kartik\daterange\DateRangePicker;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = '关键字命中规则';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <div class="col-sm-12 normalPaddingJustV">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['rule-keyword']),
                        'method' => 'get'
                    ]); ?>
                    <div class="row">
                        <div class="col-sm-4 p-r-no-away">
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
                        <div class="col-sm-3 p-l-no-away">
                            <div class="input-group m-b">
                                <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>关键字</th>
                        <th>规则</th>
                        <th>模块</th>
                        <th>命中次数</th>
                        <th>最后触发时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr>
                            <td><?= isset($model->ruleKeyword->content) ? $model->ruleKeyword->content : ''; ?></td>
                            <td><?= isset($model->rule->name) ? $model->rule->name : ''; ?></td>
                            <td><?= isset($model->rule->module) ? $model->rule->module : ''; ?></td>
                            <td><?= $model->hit ?></td>
                            <td><?= Yii::$app->formatter->asDatetime($model->updated_at) ?></td>
                            <td>无</td>
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
