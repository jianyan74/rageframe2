<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class="modal-title">视频创建</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'file_name')->textInput() ?>
        <?= $form->field($model, 'description')->textarea() ?>
        <?= $form->field($model, 'local_url')->widget('common\widgets\webuploader\Files', [
            'type' => 'videos',
            'themeConfig' => [
                'select' => false,// 选择在线图片
            ],
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],
                'accept' => [
                    'extensions' => ['rm', 'rmvb', 'wmv', 'avi', 'mpg', 'mpeg', 'mp4'],
                    'mimeTypes' => 'video/*',
                ],
                'formData' => [
                    // 保留原名称
                    'originalName' => true,
                    'drive' => 'local',
                ],
                'fileSingleSizeLimit' => 10240 * 1024 * 2,// 大小限制
                'independentUrl' => true, // 不受接管上传Url
            ]
        ])->label('永久视频')->hint('永久视频只支持 rm/rmvb/wmv/avi/mpg/mpeg/mp4 格式,大小不超过为20M, 上限 1000 个');?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>