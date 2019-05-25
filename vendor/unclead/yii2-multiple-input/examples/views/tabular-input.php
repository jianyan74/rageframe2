<?php

use yii\bootstrap\ActiveForm;
use unclead\multipleinput\TabularInput;
use yii\helpers\Html;
use unclead\multipleinput\examples\models\Item;
use unclead\multipleinput\TabularColumn;


/* @var $this \yii\web\View */
/* @var $models Item[] */
?>

<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'tabular-form',
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]) ?>

<?= TabularInput::widget([
    'models' => $models,
    'modelClass' => Item::class,
    'cloneButton' => true,
    'sortable' => true,
    'min' => 0,
    'addButtonPosition' => [
        TabularInput::POS_HEADER,
        TabularInput::POS_FOOTER,
        TabularInput::POS_ROW
    ],
    'layoutConfig' => [
        'offsetClass'   => 'col-sm-offset-4',
        'labelClass'    => 'col-sm-2',
        'wrapperClass'  => 'col-sm-10',
        'errorClass'    => 'col-sm-4'
    ],
    'attributeOptions' => [
        'enableAjaxValidation'   => true,
        'enableClientValidation' => false,
        'validateOnChange'       => false,
        'validateOnSubmit'       => true,
        'validateOnBlur'         => false,
    ],
    'form' => $form,
    'columns' => [
        [
            'name' => 'id',
            'type' => TabularColumn::TYPE_HIDDEN_INPUT
        ],
        [
            'name' => 'title',
            'title' => 'Title',
            'type' => TabularColumn::TYPE_TEXT_INPUT,
            'attributeOptions' => [
                'enableClientValidation' => true,
                'validateOnChange' => true,
            ],
            'defaultValue' => 'Test',
            'enableError' => true
        ],
        [
            'name' => 'description',
            'title' => 'Description',
        ],
        [
            'name' => 'date',
            'type'  => '\kartik\date\DatePicker',
            'title' => 'Day',
            'defaultValue' => '1970/01/01',
            'options' => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                ],

            ],
            'headerOptions' => [
                'style' => 'width: 250px;',
                'class' => 'day-css-class'
            ]
        ],
    ],
]) ?>


<?= Html::submitButton('Update', ['class' => 'btn btn-success']); ?>
<?php ActiveForm::end(); ?>
