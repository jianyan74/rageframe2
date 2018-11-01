<?php

use yii\widgets\LinkPager;
use common\helpers\AddonUrl;

$this->title = '服务层调用';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= $this->title; ?></h5>
            </div>
            <div class="ibox-content">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <td>服务层</td>
                        <td><?= $service; ?></td>
                    </tr>
                    <tr>
                        <td>子服务层</td>
                        <td><?= $childService; ?></td>
                    </tr>
                    <tr>
                        <td>服务层内调用子服务层</td>
                        <td><?= $serviceToChildService; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>