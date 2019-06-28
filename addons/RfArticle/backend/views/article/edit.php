<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use common\enums\StatusEnum;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '文章管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-1 text-right'>{label}</div><div class='col-sm-11'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'title')->textInput(); ?>
                <?= $form->field($model, 'author')->textInput(); ?>
                <?= $form->field($model, 'sort')->textInput(); ?>
                <?= $form->field($model, 'cate_id')->widget(Select2::class, [
                    'data' => $cates,
                    'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>

                <?= $form->field($model, 'cover')->widget(Files::class, [
                    'config' => [
                        // 可设置自己的上传地址, 不设置则默认地址
                        // 'server' => '',
                        'pick' => [
                            'multiple' => false,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'description')->textarea(); ?>
                <?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::class) ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'created_at')->widget(DateTimePicker::class, [
                            'language' => 'zh-CN',
                            'options' => [
                                'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $model->created_at),
                            ],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd hh:ii',
                                'todayHighlight' => true,//今日高亮
                                'autoclose' => true,//选择后自动关闭
                                'todayBtn' => true,//今日按钮显示
                            ]
                        ]);?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'position')->checkboxList($positionExplain); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'tags')->checkboxList($tags); ?>
                    </div>
                </div>
                <?= $form->field($model, 'link')->textInput(); ?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::$listExplain); ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>