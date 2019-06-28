<?php

echo "<?php\n";
?>

use yii\widgets\ActiveForm;
use common\widgets\webuploader\Files;
use common\helpers\Url;

$this->title = '参数设置';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php $html = '
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">微信分享设置</h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                 <div class="col-sm-12">
                    <?= $form->field($model, \'share_title\')->textInput(); ?>
                    <?= $form->field($model, \'share_cover\')->widget(Files::class, [
                        \'type\' => \'images\',
                        \'theme\' => \'default\',
                        \'themeConfig\' => [],
                        \'config\' => [
                            \'pick\' => [
                                \'multiple\' => false,
                            ],
                        ]
                    ]); ?>
                    <?= $form->field($model, \'share_desc\')->textarea(); ?>
                    <?= $form->field($model, \'share_link\')->textInput(); ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
';

echo $html;
?>