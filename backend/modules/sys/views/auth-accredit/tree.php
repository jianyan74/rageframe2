<?php
use yii\helpers\Url;
use common\helpers\ArrayHelper;
use common\helpers\HtmlHelper;

?>
<?php foreach($models as $k => $model){ ?>
    <tr id=<?= $model['key'] ?> name="<?= $model['name'] ?>" class="<?= $parent_key ?>">
        <td>
            <?php if (!empty($model['-'])){ ?>
                <div class="fa fa-minus-square cf" style="cursor:pointer;"></div>
            <?php } ?>
        </td>
        <td>
            <?= ArrayHelper::itemsLevel($model['level'], $models, $k)?>
            <?= $model['description']?>
            <?= HtmlHelper::a('<i class="fa fa-plus-circle"></i>', ['ajax-edit', 'parent_key' => $model['key'], 'parent_title' => $model['description'], 'level' => $model['level'] + 1], [
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
            ])?>
        </td>
        <td><?= $model['name']?></td>
        <td class="col-md-1"><?= HtmlHelper::sort($model['sort'])?></td>
        <td>
            <?= HtmlHelper::edit(['ajax-edit', 'parent_key' => $model['parent_key'], 'parent_title' => $parent_title, 'name' => $model['name'], 'level' => $model['level']], '编辑', [
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
            ])?>
            <?= HtmlHelper::delete(['delete', 'name' => $model['name']])?>
        </td>
    </tr>
    <?php if (!empty($model['-'])) { ?>
        <?= $this->render('tree', [
            'models' => $model['-'],
            'parent_title' => $model['description'],
            'parent_key' => $model['key'] . " " . $parent_key,
        ])?>
    <?php } ?>
<?php } ?>





