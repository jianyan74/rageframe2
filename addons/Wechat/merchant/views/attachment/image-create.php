<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span>
        </button>
        <h4 class="modal-title">图片创建</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'local_url')->widget('common\widgets\webuploader\Files', [
            'themeConfig' => [
                'select' => false,// 选择在线图片
            ],
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],
                'accept' => [
                    'extensions' => ['bmp', 'png', 'jpeg', 'jpg', 'gif'],
                ],
                'formData' => [
                    'drive' => 'local',
                    'writeTable' => false, // 不写表
                ],
                'fileSingleSizeLimit' => 2048 * 1024,// 图片大小限制
                'independentUrl' => true,
            ]
        ])->label('永久图片')->hint('永久图片只支持 bmp/png/jpeg/jpg/gif 格式,大小不超过为2M, 上限 5000 张'); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>