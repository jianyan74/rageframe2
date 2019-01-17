<?php
use common\helpers\AddonUrl;
use common\helpers\ArrayHelper;
use common\helpers\AddonHtmlHelper;
?>

<?php foreach($models as $k => $model){ ?>
    <tr id="<?= $model['id']?>" class="<?= $pid?>">
        <td>
            <?php if (!empty($model['-'])){ ?>
                <div class="fa fa-minus-square cf" style="cursor:pointer;"></div>
            <?php } ?>
        </td>
        <td>
            <?= ArrayHelper::itemsLevel($model['level'], $models, $k)?>
            <?= $model['title']?>
            <?= AddonHtmlHelper::a('<i class="fa fa-plus-circle"></i>', ['ajax-edit', 'pid' => $model['id'], 'parent_title' => $model['title'], 'level' => $model['level'] + 1], [
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
            ])?>
        </td>
        <td class="col-md-1"><?= AddonHtmlHelper::sort($model['sort'])?></td>
        <td>
            <?= AddonHtmlHelper::edit(['ajax-edit', 'id' => $model['id'], 'parent_title' => $parent_title, 'level' => $model['level']], '编辑', [
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
            ])?>
            <?= AddonHtmlHelper::status($model['status']); ?>
            <?= AddonHtmlHelper::delete(['delete', 'id' => $model['id']])?>
        </td>
    </tr>
    <?php if (!empty($model['-'])){ ?>
        <?= $this->render('tree', [
            'models' => $model['-'],
            'parent_title' => $model['title'],
            'pid' => $model['id']." ".$pid,
        ])?>
    <?php } ?>
<?php } ?>