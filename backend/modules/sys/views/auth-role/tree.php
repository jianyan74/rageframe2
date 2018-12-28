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
            <?= ArrayHelper::itemsLevel($model['level'], $models, $k, $treeStat)?>
            <?= $model['name']?>
            <a href="<?= Url::to(['edit','parent_key' => $model['key'], 'parent_title' => $model['name'],'level' => $model['level'] + 1])?>">
                <i class="fa fa-plus-circle"></i>
            </a>
        </td>
        <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']?>" onblur="rfSort(this)"></td>
        <td>
            <a href="<?= Url::to(['edit', 'parent_key' => $model['parent_key'], 'parent_title' => $parent_title, 'name' => $model['name'], 'level' => $model['level']])?>">
                <span class="btn btn-info btn-sm">编辑</span>
            </a>
            <a href="<?= Url::to(['delete', 'name' => $model['name']])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>
        </td>
    </tr>
    <?php if (!empty($model['-'])) { ?>
        <?= $this->render('tree', [
            'models' => $model['-'],
            'parent_title' => $model['name'],
            'parent_key' => $model['key'] . " " . $parent_key,
            'treeStat' => $treeStat,
        ])?>
    <?php } ?>
<?php } ?>