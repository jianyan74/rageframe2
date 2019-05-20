<?php
use common\helpers\Url;
use common\helpers\ArrayHelper;
use common\helpers\Html;

?>
<?php foreach($models as $k => $model){ ?>
    <tr id="<?= $model['key'] ?>" class="<?= $parent_key ?>" style="display: <?= $defaultCss; ?>" data-parentKey="<?= implode('-', explode(' ', $parent_key)) ?>">
        <td>
            <?php if (!empty($model['-'])){ ?>
                <div class="fa fa-plus-square cf" style="cursor:pointer;"></div>
            <?php } ?>
        </td>
        <td>
            <?= ArrayHelper::itemsLevel($model['level'], $models, $k)?>
            <?= $model['description']?>
            <?= Html::a('<i class="fa fa-plus-circle"></i>', ['ajax-edit', 'parent_key' => $model['key'], 'parent_title' => $model['description'], 'level' => $model['level'] + 1], [
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
            ])?>
        </td>
        <td><?= $model['name']?></td>
        <td class="col-md-1"><?= Html::sort($model['sort'])?></td>
        <td>
            <?= Html::edit(['ajax-edit', 'parent_key' => $model['parent_key'], 'parent_title' => $parent_title, 'name' => $model['name'], 'level' => $model['level']], '编辑', [
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
            ])?>
            <?= Html::delete(['delete', 'name' => $model['name']])?>
        </td>
    </tr>
    <?php if (!empty($model['-'])) { ?>
        <?= $this->render('tree', [
            'models' => $model['-'],
            'parent_title' => $model['description'],
            'parent_key' => $model['key'] . " " . $parent_key,
            'defaultCss' => 'none',
        ])?>
    <?php } ?>
<?php } ?>





