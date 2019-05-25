<?php

use \yii\helpers\Html;

/* @var $model  yii\debug\models\Router */
?>

<h1>
    Router
    <small>
        <?= Yii::$app->i18n->format('{rulesTested, plural, =0{} =1{tested # rule} other{tested # rules}} {hasMatch, plural, =0{} other{before match}}', [
            'rulesTested' => $model->count,
            'hasMatch' => (int)$model->hasMatch,
        ], 'en_US'); ?>
    </small>
</h1>

<?php if ($model->message !== null): ?>
    <div class="alert alert-info">
        <?= Html::encode($model->message); ?>
    </div>
<?php endif; ?>
<?php if ($model->logs !== []): ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Rule</th>
            <th>Parent</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model->logs as $i => $log): ?>
            <tr<?= $log['match'] ? ' class="success"' : '' ?>>
                <td><?= $i + 1; ?></td>
                <td><?= Html::encode($log['rule']); ?></td>
                <td><?= Html::encode($log['parent']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
