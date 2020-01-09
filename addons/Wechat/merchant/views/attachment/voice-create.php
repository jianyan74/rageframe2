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
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class="modal-title">音频创建</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'local_url')->widget('common\widgets\webuploader\Files', [
            'type' => 'voices',
            'themeConfig' => [
                'select' => false,// 选择在线图片
            ],
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],
                'accept' => [
                    'extensions' => ['amr', 'mp3', 'wma', 'wav', 'amr'],
                    'mimeTypes' => 'audio/*',
                ],
                'formData' => [
                    // 保留原名称
                    'originalName' => true,
                    'drive' => 'local',
                    'writeTable' => false, // 不写表
                ],
                'fileSingleSizeLimit' => 5120 * 1024,// 大小限制
                'independentUrl' => true,
            ]
        ])->label('永久音频')->hint('永久语音只支持 mp3/wma/wav/amr 格式,大小不超过为5M,长度不超过60秒, 上限 1000 个');?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>