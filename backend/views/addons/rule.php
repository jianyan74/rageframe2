<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\wechat\RuleKeyword;
use common\enums\StatusEnum;
use common\helpers\HtmlHelper;

$this->title = '规则管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= HtmlHelper::create(['rule-edit', 'addon' => Yii::$app->params['addon']['name']])?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-lg-12">
                    <?php foreach($models as $model){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span class="collapsed"><?= $model->name ?></span>
                                <span class="pull-right" id="<?= $model->id ?>">
                                        <span class="label label-info">优先级：<?= $model->sort; ?></span>
                                    <?php if (RuleKeyword::verifyTake($model->ruleKeyword)){ ?>
                                        <span class="label label-info">直接接管</span>
                                    <?php } ?>
                                    <?php if ($model->status == StatusEnum::ENABLED){ ?>
                                        <span class="label label-info" onclick="statusRule(this)">已启用</span>
                                    <?php }else{ ?>
                                        <span class="label label-danger" onclick="statusRule(this)">已禁用</span>
                                    <?php } ?>
                                 </span>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                                <div class="panel-body">
                                    <div class="col-lg-9 tooltip-demo">
                                        <?php if ($model->ruleKeyword){ ?>
                                            <?php foreach($model->ruleKeyword as $rule){
                                                if ($rule->type != RuleKeyword::TYPE_TAKE){ ?>
                                                    <span class="label label-default" data-toggle="tooltip" data-placement="bottom" title="<?= RuleKeyword::$typeExplain[$rule->type]; ?>"><?= $rule->content?></span>
                                                <?php }
                                            }
                                        } ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="btn-group pull-right">
                                            <a class="btn btn-white btn-sm" href="<?= Url::to(['rule-edit','id' => $model->id, 'addon' => Yii::$app->params['addon']['name']])?>"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-white btn-sm" href="<?= Url::to(['rule-delete','id' => $model->id, 'addon' => Yii::$app->params['addon']['name']])?>" onclick="rfDelete(this);return false;"><i class="fa fa-times"></i> 删除</a>
                                            <!-- <a class="btn btn-white btn-sm" href="#"><i class="fa fa-bar-chart-o"></i> 使用率走势</a>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="box-footer">
                <?= LinkPager::widget([
                    'pagination' => $pages
                ]);?>
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