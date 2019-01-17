<?php
use yii\helpers\Url;
use common\helpers\ArrayHelper;
use common\helpers\HtmlHelper;

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
            <!--禁止显示二级分类再次添加三级分类-->
            <?php if ($model['pid'] == 0){ ?>
                <?= HtmlHelper::a('<i class="fa fa-plus-circle"></i>', ['ajax-edit', 'pid' => $model['id'], 'parent_title' => $model['title'], 'level' => $model['level'] + 1], [
                    'data-toggle' => 'modal',
                    'data-target' => '#ajaxModal',
                ])?>
            <?php } ?>
        </td>
        <td class="col-md-1"><?= HtmlHelper::sort($model['sort'])?></td>
        <td>
            <?= HtmlHelper::edit(['ajax-edit', 'id' => $model['id'], 'parent_title' => $parent_title, 'level' => $model['level']], '编辑', [
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
            ])?>
            <?= HtmlHelper::status($model['status']); ?>
            <?= HtmlHelper::delete(['delete', 'id' => $model['id']])?>
        </td>
    </tr>
    <?php if (!empty($model['-'])){ ?>
        <?= $this->render('tree', [
            'models' => $model['-'],
            'parent_title' => $model['title'],
            'pid' => $model['id']." ".$pid,
        ]) ?>
    <?php } ?>
<?php } ?>