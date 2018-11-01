<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

$this->title = '配置管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a href="<?= Url::to(['config/index'])?>"> 配置管理</a></li>
            <li><a href="<?= Url::to(['config-cate/index'])?>"> 配置分类</a></li>
            <a class="btn btn-primary btn-xs pull-right h6" href="<?= Url::to(['edit'])?>" data-toggle='modal' data-target='#ajaxModal'>
                <i class="fa fa-plus"></i> 创建
            </a>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <form action="" method="get" class="form-horizontal" role="form" id="form">
                                <div class="col-sm-5">
                                    <?= Html::dropDownList('cate_id', $cate_id, ArrayHelper::merge(['' => '全部'], \common\models\sys\ConfigCate::getList()), ['class' => 'form-control']);?>
                                </div>
                                <div class="input-group m-b">
                                    <input type="text" class="form-control" name="keyword" placeholder="<?= $keyword ? $keyword : '请输入标题/标识'?>"/>
                                    <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>标题</th>
                                <th>标识</th>
                                <th>排序</th>
                                <th>分组</th>
                                <th>类型</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($models as $model){ ?>
                                <tr id = <?= $model->id; ?>>
                                    <td><?= $model->id; ?></td>
                                    <td><?= $model->title; ?></td>
                                    <td><a href="<?= Url::to(['edit','id'=>$model->id])?>" data-toggle='modal' data-target='#ajaxModal'><?= $model->name; ?></a></td>
                                    <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']; ?>" onblur="rfSort(this)"></td>
                                    <td><?= isset($model['cate']['title']) ? $model['cate']['title'] : '' ?></td>
                                    <td><?= Yii::$app->params['configTypeList'][$model->type] ?></td>
                                    <td>
                                        <a href="<?= Url::to(['edit','id' => $model->id])?>" data-toggle='modal' data-target='#ajaxModal'><span class="btn btn-info btn-sm">编辑</span></a>
                                        <?= \common\helpers\HtmlHelper::statusSpan($model['status']); ?>
                                        <a href="<?= Url::to(['delete','id'=>$model->id])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
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