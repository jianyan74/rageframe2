<?php
use yii\helpers\Url;
use common\helpers\ArrayHelper;

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
            <?php if ($model['level'] <= 2){ ?>
                <a href="<?= Url::to(['edit','pid' => $model['id'], 'cate_id' => $cate_id, 'parent_title' => $model['title'], 'level' => $model['level'] + 1])?>" data-toggle='modal' data-target='#ajaxModal'>
                    <i class="fa fa-plus-circle"></i>
                </a>
            <?php } ?>
        </td>
        <td><?= $model['url']?></td>
        <td><div class="fa <?= $model['menu_css']?>"></div></td>
        <td>
            <?php if (!empty($model['dev'])){ ?>
                <span class="label label-info">显示</span>
            <?php } ?>
        </td>
        <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']; ?>" onblur="rfSort(this)"></td>
        <td>
            <a href="<?= Url::to(['edit','id' => $model['id'], 'cate_id' => $cate_id, 'parent_title' => $parent_title, 'level' => $model['level']])?>" data-toggle='modal' data-target='#ajaxModal'>
                <span class="btn btn-info btn-sm">编辑</span>
            </a>
            <?= \common\helpers\HtmlHelper::statusSpan($model['status']); ?>
            <a href="<?= Url::to(['delete','id' => $model['id'], 'cate_id' => $cate_id])?>"  onclick="rfDelete(this);return false;">
                <span class="btn btn-warning btn-sm">删除</span>
            </a>
        </td>
    </tr>
    <?php if (!empty($model['-'])){ ?>
        <?= $this->render('tree', [
            'models' => $model['-'],
            'parent_title' => $model['title'],
            'pid' => $model['id']." ".$pid,
            'cate_id' => $cate_id
        ])?>
    <?php } ?>
<?php } ?>