<?php

use yii\bootstrap\ActiveForm;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\examples\models\ExampleModel;
use yii\helpers\Html;
use unclead\multipleinput\MultipleInputColumn;

// Note: You have to install https://github.com/kartik-v/yii2-widget-datepicker for correct work an example
use kartik\date\DatePicker;

/* @var $this \yii\web\View */
/* @var $model ExampleModel */
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]);?>


<h3>Single column</h3>
<?php
    echo $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'max'  => 6,
        'allowEmptyList' => false,
        'sortable' => true,
        'columns' => [
            [
                'name' => 'emails',
                'options' => [
                    'placeholder' => 'E-mail'
                ]
            ]
        ],
        'min'  => 2, // should be at least 2 rows
        'addButtonPosition' => [
            MultipleInput::POS_HEADER,
            MultipleInput::POS_FOOTER,
            MultipleInput::POS_ROW
        ]
    ])
    ->label(false);
?>

<h3>Multiple columns</h3>
<?php
echo $form->field($model, 'schedule')->label(false)->widget(MultipleInput::className(), [
    'id' => 'examplemodel-schedule',
    'max' => 4,
    'sortable' => true,
    'allowEmptyList' => true,
    'showGeneralError' => true,
    'rowOptions' => function($model) {
        $options = [];

        if ($model['priority'] > 1) {
            $options['class'] = 'danger';
        }
        return $options;
    },
    'cloneButton' => true,

    'columns' => [
        [
            'name'  => 'user_id',
            'type'  => MultipleInputColumn::TYPE_DROPDOWN,
            'enableError' => true,
            'title' => 'User',
            'defaultValue' => 33,
            /* it can be an anonymous function
            'items' => function($data) {
                return [
                    31 => 'item 31',
                    32 => 'item 32',
                    33 => 'item 33',
                    34 => 'item 34',
                    35 => 'item 35',
                    36 => 'item 36',
                ];
            }
            */
            'items' =>  [
                31 => 'item 31',
                32 => 'item 32',
                33 => 'item 33',
                34 => 'item 34',
                35 => 'item 35',
                36 => 'item 36',
            ]
        ],
        [
            'name'  => 'day',
            'type'  => DatePicker::className(),
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
            ],
            'headerOptions' => [
                'style' => 'width: 250px;',
                'class' => 'day-css-class'
            ],
        ],
        [
            'name'  => 'priority',
            'title' => 'Priority',
            'defaultValue' => 1,
            'enableError' => true,
            'options' => [
                'class' => 'input-priority'
            ]
        ],
        [
            'name'  => 'comment', // can be ommited in case of static column
            'type'  => MultipleInputColumn::TYPE_STATIC,
            'value' => function($data) {
                return Html::tag('span', 'static content', ['class' => 'label label-info']);
            },
            'headerOptions' => [
                'style' => 'width: 70px;',
            ]
        ],
        [
            'type' => MultipleInputColumn::TYPE_CHECKBOX_LIST,
            'name' => 'enable',
            'headerOptions' => [
                'style' => 'width: 80px;',
            ],
            'items' => [
                1 => 'Test 1',
                2 => 'Test 2',
                3 => 'Test 3',
                4 => 'Test 4'
            ],
            'options' => [
                // see checkboxList implementation in the BaseHtml helper for getting more detail
                'unselect' => 2
            ]
        ]
    ]
]);

?>

<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>


<?php
$js = <<< JS
        $('#examplemodel-schedule').on('afterInit', function(){
            console.log('calls on after initialization event');
        }).on('beforeAddRow', function(e) {
            console.log('calls on before add row event');
            return confirm('Are you sure you want to add row?')
        }).on('afterAddRow', function(e, row) {
            console.log('calls on after add row event', $(row));
        }).on('beforeDeleteRow', function(e, item){
            console.log(item);
            console.log('calls on before remove row event');
            return confirm('Are you sure you want to delete row?')
        }).on('afterDeleteRow', function(e, item){       
            console.log('calls on after remove row event');
            console.log('User_id:' + item.find('.list-cell__user_id').find('select').first().val());
        }).on('afterDropRow', function(e, item){       
            console.log('calls on after drop row', item);
        });
JS;

$this->registerJs($js);
