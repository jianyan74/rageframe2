<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use common\helpers\HtmlHelper;
use yii\widgets\ActiveForm;
use common\models\wechat\MsgHistory;
use common\models\wechat\Rule;

$this->title = '历史消息';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    总计 <strong class="text-danger"><?= $pages->totalCount ?></strong> 条
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="row">
                    <div class="col-sm-3">
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['index']),
                            'method' => 'get'
                        ]); ?>
                        <div class="input-group m-b">
                            <?= Html::textInput('keywords   ', $keywords, [
                                'placeholder' => '请输入内容',
                                'class' => 'form-control'
                            ])?>
                            <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>昵称</th>
                        <th>发送类别</th>
                        <th>内容</th>
                        <th>规则</th>
                        <th>触发回复</th>
                        <th>粉丝编号</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr>
                            <td><?= $model->id; ?></td>
                            <td><?= isset($model->fans->nickname) ? $model->fans->nickname : '' ?></td>
                            <td><?= $model->type?></td>
                            <td style="max-width:515px; overflow:hidden; word-break:break-all; word-wrap:break-word;"><?= Html::encode(MsgHistory::readMessage($model->type,$model->message)) ?></td>
                            <td>
                                <?php if(!$model->rule_id){ ?>
                                    <span class="label label-default">未触发</span>
                                <?php }else{ ?>
                                    <span class="label label-info"><?= Rule::findRuleTitle($model->rule_id)?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if(!$model->module){ ?>
                                    <span class="label label-default">未触发</span>
                                <?php }else{ ?>
                                    <span class="label label-info"><?= isset(Rule::$moduleExplain[$model->module]) ? Rule::$moduleExplain[$model->module] : $model->module . '模块'; ?></span>
                                <?php } ?>
                            </td>
                            <td><?= $model->openid; ?></td>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                            <td>
                            <td>
                                <?= HtmlHelper::linkButton(['/wechat/fans/send-message','openid' => $model->openid], '发送消息', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ])?>
                                <?= HtmlHelper::delete(['delete','id' => $model->id]);?>
                            </td>
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