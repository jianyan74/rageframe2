<?php

use common\helpers\Url;
use yii\widgets\LinkPager;
use common\models\wechat\Menu;
use common\helpers\Html;
use common\helpers\Auth;

$this->title = Menu::$typeExplain[$type];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach ($types as $key => $value) { ?>
                    <li <?php if ($key == $type){ ?>class="active"<?php } ?>><a
                                href="<?= Url::to(['index', 'type' => $key]) ?>"> <?= $value ?></a></li>
                <?php } ?>
                <li class="pull-right">
                    <div class="row">
                        <div class="col-lg-12 normalPaddingTop">
                            <!-- 权限校验判断 -->
                            <?php if (Auth::verify('/wechat/menu/sync')) { ?>
                                <a class="btn btn-primary btn-xs" id="getNewMenu">
                                    <i class="fa fa-cloud-download"></i> 同步
                                </a>
                            <?php } ?>
                            <?= Html::create(['edit', 'type' => $type]) ?>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>标题</th>
                            <th>显示对象</th>
                            <th>是否在微信生效</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($models as $model) { ?>
                            <tr>
                                <td><?= $model->id ?></td>
                                <td><?= $model->title ?></td>
                                <td>
                                    <?php if ($model->type == 1) { ?>
                                        全部粉丝
                                    <?php } else { ?>
                                        性别: <?= Yii::$app->params['individuationMenuSex'][$model->sex]; ?><br>
                                        手机系统: <?= Yii::$app->params['individuationMenuClientPlatformType'][$model->client_platform_type]; ?>
                                        <br>
                                        语言: <?= Yii::$app->params['individuationMenuLanguage'][$model->language]; ?><br>
                                        标签: <?= empty($model->tag_id) ? '全部粉丝' : Yii::$app->services->wechatFansTags->findById($model->tag_id)['name']; ?>
                                        <br>
                                        地区: <?= empty($model->province . $model->city) ? '不限' : $model->province . '·' . $model->city; ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($model->status == 1) { ?>
                                        <span class="text-success">菜单生效中</span>
                                    <?php } else { ?>
                                        <a href="<?= Url::to(['save', 'id' => $model->id]) ?>" class="color-default">生效并置顶</a>
                                    <?php } ?>
                                </td>
                                <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                                <td>
                                    <?= Html::edit(['edit', 'id' => $model->id, 'type' => $model->type],
                                        $model->type == 2 ? '查看' : '编辑'); ?>
                                    <?php if ($model->status == 0 || $model->type == 2) { ?>
                                        <?= Html::delete(['delete', 'id' => $model->id, 'type' => $model->type]); ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= LinkPager::widget([
                                'pagination' => $pages,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    // 获取资源
    $("#getNewMenu").click(function () {
        rfAffirm('同步中,请不要关闭当前页面');
        sync();
    });

    // 同步菜单
    function sync() {
        $.ajax({
            type: "get",
            url: "<?= Url::to(['sync'])?>",
            dataType: "json",
            success: function (data) {
                if (data.code == 200) {
                    rfAffirm(data.message);
                    window.location.reload();
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>