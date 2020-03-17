<?php
use common\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use addons\Wechat\common\models\Fans;
use common\helpers\Auth;
use common\helpers\Html;

$this->title = '粉丝管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="tabs-container">
            <div class="tabs-right">
                <ul class="nav nav-tabs">
                    <li <?php if ($tag_id == ''){ ?>class="active"<?php } ?>>
                        <a href="<?= Url::to(['index'])?>"> 全部粉丝(<strong class="text-danger"><?= $all_fans ?></strong>)</a>
                    </li>
                    <?php foreach ($fansTags as $k => $tag){ ?>
                        <li <?php if ($tag['id'] == $tag_id){ ?>class="active"<?php } ?>>
                            <a href="<?= Url::to(['index','tag_id' => $tag['id']])?>"> <?= $tag['name'] ?>(<strong class="text-danger"><?= $tag['count'] ?></strong>)</a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="panel-body">
                            <div class="col-sm-6 m-l-n-md">
                                <?php $form = ActiveForm::begin([
                                    'action' => Url::to(['index']),
                                    'method' => 'get'
                                ]); ?>
                                <div class="col-sm-5">
                                    <?= Html::dropDownList('follow', $follow, [1 => '已关注', -1 => '未关注'], ['class' => 'form-control']);?>
                                </div>
                                <div class="input-group m-b">
                                    <?= Html::textInput('keyword', $keyword, [
                                        'placeholder' => '请输入昵称/粉丝编号',
                                        'class' => 'form-control'
                                    ])?>
                                    <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                            <div class="col-sm-6">
                                <div class="pull-right">
                                    <!-- 权限校验判断 -->
                                    <?php if(Auth::verify('/wechat/fans/sync')){ ?>
                                        <span class="btn btn-white btn-sm" id="sync"><i class="fa fa-cloud-download"></i> 同步选中粉丝信息</span>
                                    <?php } ?>
                                    <!-- 权限校验判断 -->
                                    <?php if(Auth::verify('/wechat/fans/get-all-fans')){ ?>
                                        <span class="btn btn-white btn-sm" onclick="getAllFans()"><i class="fa fa-cloud-download"></i>  同步全部粉丝信息</span>
                                    <?php } ?>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="check-all"></th>
                                    <th>头像</th>
                                    <th>昵称</th>
                                    <th>性别</th>
                                    <th>是否关注</th>
                                    <th>关注/取消时间</th>
                                    <th>粉丝标签</th>
                                    <th>粉丝编号</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="list">
                                <?php foreach($models as $model){ ?>
                                    <tr openid = "<?= $model->openid; ?>">
                                        <td><input type="checkbox" name="openid[]" value="<?= $model['openid']?>"></td>
                                        <td class="feed-element">
                                            <img src="<?= $model->head_portrait ?>" class="img-circle rf-img-md img-bordered-sm">
                                        </td>
                                        <td><?= Html::encode($model->nickname) ?></td>
                                        <td><?= $model->sex == 1 ? '男' : '女' ?></td>
                                        <td>
                                            <?php if ($model->follow == Fans::FOLLOW_OFF){ ?>
                                                <span class="label label-danger">已取消</span>
                                            <?php }else{ ?>
                                                <span class="label label-info">已关注</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($model->follow == Fans::FOLLOW_OFF){ ?>
                                                <?= Yii::$app->formatter->asDatetime($model->unfollowtime) ?>
                                            <?php }else{ ?>
                                                <?= Yii::$app->formatter->asDatetime($model->followtime) ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($model['tags']){ ?>
                                                <?php foreach ($model['tags'] as $value){ ?>
                                                    <span class="label label-success"><?= $allTag[$value['tag_id']]; ?></span>
                                                <?php } ?>
                                            <?php }else{ ?>
                                                <span class="label label-default">无标签</span>
                                            <?php } ?>
                                            <!-- 权限校验判断 -->
                                            <?php if (Auth::verify('/wechat/fans/move-tag')){ ?>
                                                <a  href="<?= Url::to(['move-tag','fan_id' => $model->id])?>" data-toggle='modal' data-target='#ajaxModal' style="color: #76838f"><i class="icon ion-arrow-down-b "></i></a>
                                            <?php } ?>
                                        </td>
                                        <td><?= $model->openid ?></td>
                                        <td>
                                            <?= Html::linkButton(['send-message','openid' => $model->openid], '发送消息', [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#ajaxModalLg',
                                            ])?>
                                            <?= Html::linkButton(['view','id' => $model->id], '用户详情', [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#ajaxModal',
                                            ])?>
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
    </div>
</div>

<script>
    // 同步所有粉丝openid
    function getAllFans() {

        rfAffirm('同步中,请不要关闭当前页面');

        $.ajax({
            type:"get",
            url:"<?= Url::to(['sync-all-openid'])?>",
            dataType: "json",
            data: {},
            success: function(data){
                sync('all');
            }
        });
    }

    // 同步粉丝资料
    function sync(type, page = 0, openids = null){
        $.ajax({
            type:"post",
            url:"<?= Url::to(['sync'])?>",
            dataType: "json",
            data: {type:type,page:page,openids:openids},
            success: function(data){
                if (data.code == 200 && data.data.page) {
                    sync(type, data.data.page);
                } else {
                    rfAffirm(data.message);
                    window.location.reload();
                }
            }
        });
    }

    // 同步选中的粉丝
    $("#sync").click(function () {
        var openids = [];
        $("#list :checkbox").each(function () {
            if(this.checked){
                var openid = $(this).val();
                openids.push(openid);
            }
        });

        sync('check', 0, openids);
    });

    // 多选框选择
    $(".check-all").click(function(){
        if(this.checked){
            $("#list :checkbox").prop("checked", true);
        }else{
            $("#list :checkbox").prop("checked", false);
        }
    });
</script>