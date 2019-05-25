<?php

use yii\bootstrap\ActiveForm;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\examples\models\ExampleModel;
use yii\helpers\Html;
use unclead\multipleinput\MultipleInputColumn;


/* @var $this \yii\web\View */
/* @var $model ExampleModel */

$commonAttributeOptions = [
    'enableAjaxValidation'   => true,
    'enableClientValidation' => false,
    'validateOnChange'       => false,
    'validateOnSubmit'       => true,
    'validateOnBlur'         => false,
];

$enableActiveForm = true;
?>

<?php

if ($enableActiveForm) {
    $form = ActiveForm::begin([
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,
        'validateOnChange'          => false,
        'validateOnSubmit'          => true,
        'validateOnBlur'            => false,
    ]);
} else {
    echo Html::beginForm();
}

?>

<?php

echo MultipleInput::widget([
    'model' => $model,
    'attribute' => 'questions',
    'attributeOptions' => $commonAttributeOptions,
    'columns' => [
        [
            'name' => 'question',
            'type' => 'textarea',
        ],
        [
            'name' => 'answers',
            'type'  => MultipleInput::className(),
            'options' => [
                'attributeOptions' => $commonAttributeOptions,
                'columns' => [
                    [
                        'name' => 'right',
                        'type' => MultipleInputColumn::TYPE_CHECKBOX
                    ],
                    [
                        'name' => 'answer'
                    ]
                ]
            ]
        ]
    ],
]);
?>

<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php
if ($enableActiveForm) {
    ActiveForm::end();
} else {
    echo Html::endForm();
}
?>