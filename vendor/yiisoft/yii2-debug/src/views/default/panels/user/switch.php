<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\debug\UserswitchAsset;
use yii\grid\GridView;

/* @var $this \yii\web\View */
/* @var $panel yii\debug\panels\UserPanel */

UserswitchAsset::register($this);
?>
    <h2>Switch user</h2>
    <div class="row">
        <div class="col-sm-7">
            <?php $formSet = ActiveForm::begin([
                'action'  => \yii\helpers\Url::to(['user/set-identity']),
                'layout'  => 'horizontal',
                'options' => [
                    'id'    => 'debug-userswitch__set-identity',
                    'style' => $panel->canSearchUsers() ? 'display:none' : ''
                ]
            ]);
            echo $formSet->field(
                $panel->userSwitch,
                'user[id]', ['options' => ['class' => '']])
                ->textInput(['id' => 'user_id', 'name' => 'user_id'])
                ->label('Switch User');
            echo Html::submitButton('Switch', ['class' => 'btn btn-primary']);
            ActiveForm::end();
            ?>

        </div>
        <div class="col-sm-5">
            <?php
            if (!$panel->userSwitch->isMainUser()) {
                $formReset = ActiveForm::begin([
                    'action'  => \yii\helpers\Url::to(['user/reset-identity']),
                    'options' => [
                        'id' => 'debug-userswitch__reset-identity',
                    ]
                ]);
                echo Html::submitButton('Reset to <span class="yii-debug-toolbar__label yii-debug-toolbar__label_info">' .
                    $panel->userSwitch->getMainUser()->getId() .
                    '</span>', ['class' => 'btn btn-default']);
                ActiveForm::end();
            }
            ?>
        </div>
    </div>

<?php
if ($panel->canSearchUsers()) {
    yii\widgets\Pjax::begin(['id' => 'debug-userswitch__filter', 'timeout' => false]);
    echo GridView::widget([
        'dataProvider' => $panel->getUserDataProvider(),
        'filterModel'  => $panel->getUsersFilterModel(),
        'tableOptions' => [
            'class' => 'table table-bordered table-responsive table-hover table-pointer'
        ],
        'columns'      => $panel->filterColumns
    ]);
    yii\widgets\Pjax::end();
}
?>