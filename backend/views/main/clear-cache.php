<?php
$this->title = "缓存清理";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox-content m-b-sm border-bottom">
        <div class="p-xs">
            <div class="pull-left m-r-md">
                <i class="fa fa-globe text-navy mid-icon"></i>
            </div>
            <?php if ($status == false) { ?>
                <h2>缓存清理成功！</h2>
                <span>SUCCESS</span>
            <?php }else{ ?>
                <h2>缓存清理失败,请开启 <?= $status ?> 读写权限</h2>
                <span>ERROR</span>
            <?php } ?>
        </div>
    </div>
</div>