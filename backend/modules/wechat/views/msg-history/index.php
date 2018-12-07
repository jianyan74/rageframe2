<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\wechat\MsgHistory;
use common\models\wechat\Rule;

$this->title = '历史消息';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>历史消息</h5>
                    <div class="ibox-tools">
                        总计 <strong class="text-danger"><?= $pages->totalCount ?></strong> 条
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <form action="" method="get" class="form-horizontal" role="form" id="form">
                                <div class="input-group m-b">
                                    <input type="text" class="form-control" name="keywords" placeholder="<?= $keywords ? $keywords : '请输入内容'?>"/>
                                    <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                                </div>
                            </form>
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
                                <td style="max-width:515px; overflow:hidden; word-break:break-all; word-wrap:break-word;"><?= MsgHistory::readMessage($model->type,$model->message)?></td>
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
                                    <a href="<?= Url::to(['delete','id' => $model->id])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
                                </td>
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
