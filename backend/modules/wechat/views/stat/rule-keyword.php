<?php
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

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>查询</h5>
                </div>
                <div class="ibox-content">
                    <form action="" method="get" class="form-horizontal" role="form" id="form">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 control-label">日期范围</label>
                            <div class="col-sm-8 input-group">
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
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 control-label"></label>
                            <span class="input-group-btn">
                                <button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>关键字命中规则</h5>
                </div>
                <div class="ibox-content">
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
                    <div class="row">
                        <div class="col-sm-12">
                            <?= LinkPager::widget([
                                'pagination'        => $pages,
                                'maxButtonCount'    => 5,
                                'firstPageLabel'    => "首页",
                                'lastPageLabel'     => "尾页",
                                'nextPageLabel'     => "下一页",
                                'prevPageLabel'     => "上一页",
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
