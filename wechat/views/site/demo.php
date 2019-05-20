<?php
use common\helpers\Html;
?>
<?= Html::jsFile('@web/resources/js/jquery.min.js')?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>

<input type="text" value="<?= $content?>">
<span class="pay btn btn-primary" style="padding-top: 50%">立即支付</span>
