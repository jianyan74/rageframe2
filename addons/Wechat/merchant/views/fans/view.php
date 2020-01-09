<?php
use addons\Wechat\common\models\Fans;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">详细信息</h4>
</div>
<div class="modal-body">
    <table class="table text-center">
        <tbody>
        <tr>
            <td class="feed-element" >
                <img src="<?= $model->head_portrait ?>" class="img-circle img-bordered-sm" width="48" height="48">
            </td>
            <td><?= $model['nickname']?></td>
        </tr>
        <tr>
            <td>粉丝编号</td>
            <td><?= $model['openid']?></td>
        </tr>
        <tr>
            <td>性别</td>
            <td><?= $model->sex == 1 ? '男' : '女' ?></td>
        </tr>
        <tr>
            <td>是否关注</td>
            <td>
                <?php if ($model->follow == Fans::FOLLOW_OFF){ ?>
                    <span class="label label-danger">已取消</span>
                <?php }else{ ?>
                    <span class="label label-info">已关注</span>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td>关注/取消时间</td>
            <td>
                <?php if ($model->follow == Fans::FOLLOW_OFF){ ?>
                    <?= Yii::$app->formatter->asDatetime($model->unfollowtime) ?>
                <?php }else{ ?>
                    <?= Yii::$app->formatter->asDatetime($model->followtime) ?>
                <?php } ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>