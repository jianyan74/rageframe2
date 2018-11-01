<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    //数组内为jssdk授权可用的方法，按需添加，详细查看微信jssdk的方法
    wx.config(<?= $app->jssdk->buildConfig(array('updateAppMessageShareData', 'updateTimelineShareData'), false) ?>);
</script>

<script>
    wx.ready(function () {
        sharedata = {
            title: "<?= $config['share_title']?>",
            link: "<?= $config['share_link']?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "<?= $config['share_cover']?>", // 分享图标
        };

        //需在用户可能点击分享按钮前就先调用
        wx.updateTimelineShareData(sharedata, function(res) {
            //这里是回调函数
        });
        wx.updateAppMessageShareData(sharedata, function(res) {
            //这里是回调函数
        });
    });
</script>
