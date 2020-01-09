<?php

use common\helpers\Url;
use common\helpers\Html;

$this->title = '队列监控';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>标识</th>
                            <th>等待执行</th>
                            <th>延时执行</th>
                            <th>待重新执行</th>
                            <th>已完成</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $key => $datum){ ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= explode(':', $datum[1])[1] ?? 0 ?></td>
                                <td><?= explode(':', $datum[2])[1] ?? 0 ?></td>
                                <td><?= explode(':', $datum[3])[1] ?? 0 ?></td>
                                <td><?= explode(':', $datum[4])[1] ?? 0 ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>
