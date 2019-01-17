<?php
$this->title = "缓存清理";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-default">
    <!-- /.box-header -->
    <?php if ($result == true) { ?>
        <div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-check"></i> 缓存清理成功</h4>
            可手动关闭当前标签页
        </div>
    <?php }else{ ?>
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> 缓存清理失败</h4>
            请检查自己的服务器/文件夹清理权限
        </div>
    <?php } ?>
    <!-- /.box-body -->
</div>