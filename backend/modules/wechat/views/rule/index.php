<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\wechat\RuleKeyword;
use common\enums\StatusEnum;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = '自动回复';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['rule/index'])?>"> 关键字自动回复</a></li>
                <li><a href="<?= Url::to(['setting/special-message'])?>"> 非文字自动回复</a></li>
                <li><a href="<?= Url::to(['reply-default/index'])?>"> 关注/默认回复</a></li>
                <li class="pull-right">
                    <a class="btn btn-primary btn-xs" onclick="openEdit()">
                        <i class="fa fa-plus"></i> 创建
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="btn-group">
                                <a class="btn <?= !$module ? 'btn-primary': 'btn-white' ;?>" href="<?= Url::to(['index'])?>">全部</a>
                                <?php foreach ($modules as $key => $mo){ ?>
                                    <a class="btn <?= $module == $key ? 'btn-primary': 'btn-white' ;?>" href="<?= Url::to(['index','module'=>$key])?>"><?= $mo?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['index']),
                                'method' => 'get'
                            ]); ?>
                            <div class="input-group m-b">
                                <?= Html::textInput('keyword', $keyword, [
                                    'placeholder' => '请输入规则',
                                    'class' => 'form-control'
                                ])?>
                                <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <?php foreach($models as $model){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span class="collapsed"><?= $model->name ?></span>
                                <span class="pull-right" id="<?= $model->id ?>">
                                            <span class="label label-info">优先级：<?= $model->sort; ?></span>
                                    <?php if(RuleKeyword::verifyTake($model->ruleKeyword)){ ?>
                                        <span class="label label-info">直接接管</span>
                                    <?php } ?>
                                    <?php if($model->status == StatusEnum::ENABLED){ ?>
                                        <span class="label label-info" onclick="statusRule(this)">已启用</span>
                                    <?php }else{ ?>
                                        <span class="label label-danger" onclick="statusRule(this)">已禁用</span>

                                    <?php } ?>
                                        </span>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                                <div class="panel-body">
                                    <div class="col-lg-9 tooltip-demo">
                                        <?php if($model->ruleKeyword){ ?>
                                            <?php foreach($model->ruleKeyword as $rule){
                                                if($rule->type != RuleKeyword::TYPE_TAKE){ ?>
                                                    <span class="label label-default" data-toggle="tooltip" data-placement="bottom" title="<?= RuleKeyword::$typeExplain[$rule->type]; ?>"><?= $rule->content?></span>
                                                <?php }
                                            }
                                        } ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="btn-group pull-right">
                                            <a class="btn btn-white btn-sm" href="<?= Url::to(['edit','id'=>$model->id, 'module' => $model->module])?>"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-white btn-sm" href="<?= Url::to(['delete','id' => $model->id])?>" onclick="rfDelete(this);return false;"><i class="fa fa-times"></i> 删除</a>
                                            <!-- <a class="btn btn-white btn-sm" href="#"><i class="fa fa-bar-chart-o"></i> 使用率走势</a>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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

<script type="text/html"  id="editModel">
    <?php foreach($modules as $key => $module){ ?>
        <div class="col-lg-12 text-center" style="padding: 10px">
            <a href="<?= Url::to(['edit', 'module' => $key]); ?>" class="btn btn-w-m btn-info"><?= $module; ?></a>
        </div>
    <?php } ?>
</script>
<script>
    function openEdit(){
        var html = template('editModel', []);
        //自定页
        layer.open({
            type: 1,
            title: '创建规则类型',
            skin: 'layui-layer-demo', //样式类名
            closeBtn: false, //不显示关闭按钮
            shift: 2,
            area: ['250px', '380px'],
            shadeClose: true, //开启遮罩关闭
            content: html
        });
    }

    // status => 1:启用;-1禁用;
    function statusRule(obj){

        var id = $(obj).parent().attr('id');
        var self = $(obj);
        var status = self.hasClass("label-danger") ? 1 : 0;

        $.ajax({
            type:"get",
            url:"<?= Url::to(['ajax-update'])?>",
            dataType: "json",
            data: {id:id,status:status},
            success: function(data){
                if(data.code == 200) {
                    if(self.hasClass("label-danger")){
                        self.removeClass("label-danger").addClass("label-info");
                        self.text('已启用');
                    } else {
                        self.removeClass("label-info").addClass("label-danger");
                        self.text('已禁用');
                    }
                }else{
                    alert(data.message);
                }
            }
        });
    }
</script>