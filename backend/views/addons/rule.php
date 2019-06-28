<?php
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\wechat\RuleKeyword;
use common\enums\StatusEnum;

$this->title = '规则回复';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::a('<i class="fa fa-plus"></i> 创建', ['/addons/rule-edit', 'addon' => $addonName], [
                        'class' => 'btn btn-primary btn-xs'
                    ]) ?>
                </div>
            </div>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['/addons/rule', 'addon' => $addonName]),
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
                    <?php foreach($models as $model){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span class="collapsed"><?= $model->name ?></span>
                                <span class="pull-right" id="<?= $model->id ?>">
                                    <span class="label label-info">优先级：<?= $model->sort; ?></span>
                                    <?php if(Yii::$app->services->wechatRuleKeyword->verifyTake($model->ruleKeyword)){ ?>
                                        <span class="label label-info">直接接管</span>
                                    <?php } ?>
                                    <?php if($model->status == StatusEnum::ENABLED){ ?>
                                        <span class="label label-info pointer" onclick="statusRule(this)">已启用</span>
                                    <?php }else{ ?>
                                        <span class="label label-danger pointer" onclick="statusRule(this)">已禁用</span>
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
                                            <a class="btn btn-white btn-sm" href="<?= Url::to(['/addons/rule-edit', 'addon' => $addonName, 'id' => $model->id]); ?>"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-white btn-sm" href="<?= Url::to(['/addonsrule-delete', 'addon' => $addonName, 'id' => $model->id]); ?>" onclick="rfDelete(this);return false;"><i class="fa fa-times"></i> 删除</a>
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

<script>
    // status => 1:启用;-1禁用;
    function statusRule(obj){
        var id = $(obj).parent().attr('id');
        var self = $(obj);
        var status = self.hasClass("label-danger") ? 1 : 0;

        $.ajax({
            type:"get",
            url:"<?= Url::to(['/addons/ajax-update'])?>",
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