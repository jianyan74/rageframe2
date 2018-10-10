<?php
use yii\helpers\Url;
use common\helpers\ArrayHelper;

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
            <a href="<?= Url::to(['ajax-edit','parent_key' => $model['key'], 'parent_title' => $model['description'],'level' => $model['level']+1])?>" data-toggle='modal' data-target='#ajaxModal'>
                <i class="fa fa-plus-circle"></i>
            </a>
        </td>
        <td><?= $model['name']?></td>
        <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']?>" onblur="rfSort(this)"></td>
        <td>
            <a href="<?= Url::to(['ajax-edit', 'parent_key' => $model['parent_key'], 'parent_title' => $parent_title,'name' => $model['name'], 'level' => $model['level']])?>" data-toggle='modal' data-target='#ajaxModal'>
                <span class="btn btn-info btn-sm">编辑</span>
            </a>
            <a href="<?= Url::to(['delete', 'name' => $model['name']])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
        </td>
    </tr>
    <?php if (!empty($model['-'])) { ?>
        <?= $this->render('auth_tree', [
            'models' => $model['-'],
            'parent_title' => $model['description'],
            'parent_key' => $model['key'] . " " . $parent_key,
        ])?>
    <?php } ?>
<?php } ?>





