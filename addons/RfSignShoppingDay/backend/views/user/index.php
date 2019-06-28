<?php
use yii\widgets\LinkPager;
use common\helpers\Url;

$this->title = '用户管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>头像</th>
                        <th>昵称</th>
                        <th>openid</th>
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id = <?= $model->id; ?>>
                            <td><?= $model->id; ?></td>
                            <td><img src="<?= \common\helpers\ImageHelper::defaultHeaderPortrait($model->avatar) ?>" alt="" width="45" height="45"></td>
                            <td><?= $model->nickname; ?></td>
                            <td><?= $model->openid; ?></td>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at); ?></td>
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