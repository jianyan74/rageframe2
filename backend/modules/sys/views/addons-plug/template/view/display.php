<?php

echo "<?php\n";
?>

use yii\widgets\ActiveForm;
use common\widgets\webuploader\Images;
use common\helpers\AddonUrl;

$this->title = '参数设置';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php $html = '
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>微信分享设置</h5>
            </div>
            <div class="ibox-content">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([]); ?>
                    <?= $form->field($model, \'share_title\')->textInput(); ?>
                    <?= $form->field($model, \'share_cover\')->widget(Images::className(), [
                        \'config\' => [
                            \'pick\' => [
                                \'multiple\' => false,
                            ],
                        ]
                    ]); ?>
                    <?= $form->field($model, \'share_link\')->textInput(); ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
';

echo $html;
?>