<?php

use yii\helpers\Json;
use common\helpers\DebrisHelper;
use common\helpers\Html;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table">
        <tbody>
        <tr>
            <td>设备/浏览器</td>
            <td><?= Html::encode($model['device']) ?></td>
        </tr>
        <tr>
            <td>行为</td>
            <td><?= Html::encode($model['behavior']) ?></td>
        </tr>
        <tr>
            <td>提交方法</td>
            <td><?= Html::encode($model['method']) ?></td>
        </tr>
        <tr>
            <td>用户</td>
            <td><?= isset($model->manager->username) ? $model->manager->username : '游客' ?></td>
        </tr>
        <tr>
            <td>模块</td>
            <td><?= Html::encode($model['module']) ?></td>
        </tr>
        <tr>
            <td>控制器方法</td>
            <td><?= $model['controller']; ?>/<?= $model['action']; ?></td>
        </tr>
        <tr>
            <td>Url</td>
            <td><?= Html::encode($model['url']) ?></td>
        </tr>
        <tr>
            <td>IP</td>
            <td><?= DebrisHelper::long2ip($model->ip); ?></td>
        </tr>
        <tr>
            <td>地区</td>
            <td>
                <?php
                $data = [];
                !empty($model['country']) && $data[] = $model['country'];
                !empty($model['provinces']) && $data[] = $model['provinces'];
                !empty($model['city']) && $data[] = $model['city'];
                echo implode(' · ', $data);
                ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Get数据</td>
            <td style="max-width: 700px">
                <?php
                echo "<pre>";
                print_r($model['get_data'])
                ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Post数据</td>
            <td style="max-width: 700px">
                <?php
                echo "<pre>";
                print_r($model['post_data'])
                ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Header数据</td>
            <td style="max-width: 700px">
                <?php
                echo "<pre>";
                print_r($model['header_data'])
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>