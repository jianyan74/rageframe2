<?php
use yii\helpers\Url;
use common\helpers\HtmlHelper;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

$this->title = '配置管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['config/index'])?>"> 配置管理</a></li>
                <li><a href="<?= Url::to(['config-cate/index'])?>"> 配置分类</a></li>
                <li class="pull-right">
                    <?= HtmlHelper::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ])?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="row normalPaddingJustV">
                        <div class="col-sm-4">
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['index']),
                                'method' => 'get'
                            ]); ?>
                            <div class="col-sm-5">
                                <?= HtmlHelper::dropDownList('cate_id', $cate_id, ArrayHelper::merge(['' => '全部'], $cateDropDownList), ['class' => 'form-control']);?>
                            </div>
                            <div class="input-group m-b">
                                <?= HtmlHelper::textInput('keyword', $keyword, [
                                    'placeholder' => '请输入标题/标识',
                                    'class' => 'form-control'
                                ])?>
                                <?= HtmlHelper::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>标题</th>
                            <th>标识</th>
                            <th>排序</th>
                            <th>类别</th>
                            <th>属性</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr id = <?= $model->id; ?>>
                                <td><?= $model->id; ?></td>
                                <td><?= $model->title; ?></td>
                                <td><a href="<?= Url::to(['edit','id' => $model->id])?>" data-toggle='modal' data-target='#ajaxModal'><?= $model->name; ?></a></td>
                                <td class="col-md-1"><?= HtmlHelper::sort($model['sort'])?></td>
                                <td><?= $model['cate']['title'] ?? '' ?></td>
                                <td><?= Yii::$app->params['configTypeList'][$model->type] ?></td>
                                <td>
                                    <?= HtmlHelper::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ])?>
                                    <?= HtmlHelper::status($model['status']); ?>
                                    <?= HtmlHelper::delete(['delete', 'id' => $model->id])?>
                                </td>
                            </tr>
                        <?php } ?>
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