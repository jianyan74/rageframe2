<?php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => $model->formName(),
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'content')->widget(unclead\multipleinput\MultipleInput::class, [
            'max' => 6,
            'columns' => [
                [
                    'name'  => 'user_id',
                    'type'  => 'dropDownList',
                    'title' => 'User',
                    'defaultValue' => 1,
                    'items' => [
                        1 => 'User 1',
                        2 => 'User 2'
                    ]
                ],
                [
                    'name'  => 'day',
                    'type'  => \kartik\date\DatePicker::class,
                    'title' => 'Day',
                    'value' => function($data) {
                        return $data['day'];
                    },
                    'items' => [
                        '0' => 'Saturday',
                        '1' => 'Monday'
                    ],
                    'options' => [
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true
                        ]
                    ]
                ],
                [
                    'name'  => 'priority',
                    'title' => 'Priority',
                    // 'enableError' => false,
                    'options' => [
                        'class' => 'input-priority'
                    ]
                ]
            ]
        ]);
        ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>

<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>