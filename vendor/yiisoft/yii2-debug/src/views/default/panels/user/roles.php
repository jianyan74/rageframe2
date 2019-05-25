<?php

use yii\grid\GridView;

/* @var $panel yii\debug\panels\UserPanel */

if ($panel->data['rolesProvider']) {
    echo '<h2>Roles</h2>';

    echo GridView::widget([
        'dataProvider' => $panel->data['rolesProvider'],
        'columns'      => [
            'name',
            'description',
            'ruleName',
            'data',
            'createdAt:datetime',
            'updatedAt:datetime'
        ]
    ]);
}

if ($panel->data['permissionsProvider']) {
    echo '<h2>Permissions</h2>';

    echo GridView::widget([
        'dataProvider' => $panel->data['permissionsProvider'],
        'columns'      => [
            'name',
            'description',
            'ruleName',
            'data',
            'createdAt:datetime',
            'updatedAt:datetime'
        ]
    ]);
} ?>

