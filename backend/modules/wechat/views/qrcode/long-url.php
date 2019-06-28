<?php
use common\helpers\Url;

$this->title = '长链接转二维码';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 二维码管理</a></li>
                <li><a href="<?= Url::to(['/wechat/qrcode-stat/index']) ?>"> 扫描统计</a></li>
                <li class="active"><a href="<?= Url::to(['long-url']) ?>"> 长链接转二维码</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane rf-auto">
                    <div class="form-group">
                        <label class="control-label" for="menu-title">长链接</label>
                        <div class="input-group m-b">
                            <input id="longurl" class="form-control" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" id="change">立即转换</button>
                            </span>
                        </div>
                        <div class="help-block">请输入您要转换的长链接，支持http://、https://、weixin://wxpay 格式的url</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="menu-title">二维码</label><br>
                            <div class="row" style="padding-left: 15px">
                                <img src="<?= Url::to(['qr','shortUrl'=>Yii::$app->request->hostInfo])?>" id="qrsrc" style="border:1px solid #CCC;border-radius:4px;">
                                <div class="help-block"><span id="longUrl">默认显示 <?= Yii::$app->request->hostInfo ?> 的二维码</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="menu-title">短连接</label>
                            <input id="url" class="form-control" type="text" value="" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#change').click(function(){

        var longurl = $('#longurl').val().trim();
        if (longurl == '') {
            rfAffirm('请输入长链接');
            return;
        } else if(longurl.indexOf("http://") == -1 && longurl.indexOf("https://") == -1 && longurl.indexOf("weixin://") == -1) {
            rfAffirm('请输入有效的长链接');
            return;
        }
        var change = $(this);
        var img_url = "<?= Url::to(['qr']) ?>";
        change.html('<i class="fa fa-spinner"></i> 转换中');
        $.ajax({
            type : "post",
            url : "<?= Url::to(['long-url']) ?>",
            dataType : "json",
            data : {shortUrl:longurl},
            success: function(data){
                if (data.code == 404) {
                    rfAffirm(data.message);
                } else {
                    $('#url').val(data.data.short_url);
                    $('#longUrl').text("当前显示 " + longurl + " 的二维码");
                    $('#qrsrc').attr('src', img_url + '?shortUrl=' + data.data.short_url);
                    change.html('立即转换');
                }
            }
        });
    });
</script>
