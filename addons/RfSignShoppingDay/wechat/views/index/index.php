<?php
use common\helpers\AddonHelper;

$path = AddonHelper::filePath();
$this->title = isset($config['site_title']) ? $config['site_title'] : '购物节';
?>
    <div id="wrap">
        <div class="container">
            <img src="<?= $path;?>img/logo.png" class="logo"/>
            <img src="<?= $path;?>img/slogan.png" class="slogan"/>
            <img src="<?= $path;?>img/sun.png" class="sun"/>
            <a href="javascript:void(0);" class="play"></a>
            <div class="table">
                <div class="item">
                    0
                    <img src="<?= $path;?>img/start.png"/>
                </div>
                <div class="item">1</div>
                <div class="item">2</div>
                <div class="item">
                    3
                    <img src="<?= $path;?>img/icon-1.png" />
                </div>
                <div class="item">4</div>
                <div class="item">5</div>
                <div class="item">6</div>
                <div class="item">
                    7
                    <img src="<?= $path;?>img/icon-2.png"/>
                </div>
                <div class="item">8</div>
                <div class="item">9</div>
                <div class="item">
                    10
                    <img src="<?= $path;?>img/icon-3.png"/>
                </div>
                <div class="item">11</div>
                <div class="item">12</div>
                <div class="item">13</div>
                <div class="item">14</div>
                <div class="item">
                    15
                    <img src="<?= $path;?>img/icon-4.png"/>
                </div>
                <div class="item">16</div>
                <div class="item">
                    17
                    <img src="<?= $path;?>img/end.png" class="end"/>
                    <img src="<?= $path;?>img/icon-5.png"/>
                </div>
                <img src="<?= $path;?>img/person.png" class="person"/>
            </div>

            <div class="footer">
                <div class="box">
                    <p>每日完成签到即可前进一步</p>
                    <p>已签到<span><?= $user['sign_num']; ?></span>次</p>
                </div>
                <a href="javascript:void(0);" class="signIn"></a>
                <a href="javascript:void(0);" class="signed"></a>
                <a href="javascript:void(0);" class="myPrize"></a>
            </div>
        </div>
    </div>

    <div class="modal"></div>
    <!--非会员弹窗-->
    <div class="layer layer-1">
        <div class="content">
            <h3>很抱歉</h3>
            <p class="tip">您还不是1902广场的会员</p>
            <p>成为会员，立即领奖</p>
            <a href="javascript:void(0);" class="btn-score"></a>
        </div>
    </div>

    <!--积分中奖弹窗-->
    <div class="layer layer-2">
        <div class="content">
            <h3>恭喜获得</h3>
            <p><span class="score"></span>会员积分</p>
            <a href="javascript:void(0);" class="btn-score" title="查看积分"></a>
        </div>
    </div>

    <!--卡券中奖弹窗-->
    <div class="layer layer-3">
        <div class="content">
            <h3>恭喜获得</h3>
            <p></p>
            <a href="javascript:void(0);" class="btn-coupon" title="查看卡券"></a>
        </div>
    </div>

    <!--未中奖弹窗-->
    <div class="layer layer-4">
        <div class="content">
            <p>抱歉，今天的礼<br />品已经被抢完啦！</p>
            <a href="javascript:void(0);" class="btn-sure"></a>
        </div>
    </div>

    <!--活动未开始-->
    <div class="layer layer-5">
        <div class="content">
            <p>活动未开始！</p>
            <a href="javascript:void(0);" class="btn-sure"></a>
        </div>
    </div>

    <!--活动已结束-->
    <div class="layer layer-6">
        <div class="content">
            <p>活动已结束！</p>
            <a href="javascript:void(0);" class="btn-sure"></a>
        </div>
    </div>

    <!--领奖记录-->
    <div class="layer layer-7">
        <div class="content">
            <ul>
            </ul>
        </div>
        <a href="javascript:void(0);" class="btn-sure"></a>
    </div>

    <script type="text/html" id="listTpl">
        <%each list as item i%>
        <li>
            <span class="prize"><%item.award_title%></span>
            <span class="date"><%item.created_at %></span>
        </li>
        <%/each%>
    </script>
    <script type="text/javascript" charset="utf-8">
        var path = "<?= $path;?>";
        var signUrl = "<?= \common\helpers\Url::to(['draw'])?>";
        var recordUrl = "<?= \common\helpers\Url::to(['record'])?>";
        var isMember = true;  //是否会员
        var isStart = "<?= $isStart; ?>";   //活动是否开始
        var isEnd = "<?= $isEnd; ?>";    //活动是否结束
        var signed = "<?= !empty($isSign);?>";   //今日是否已签到,默认false未签到
        var signInDays = "<?= $user['sign_num'] ?>";   //已签到天数，默认0
    </script>

<!--分享-->
<?= \common\helpers\WechatHelper::share([
    'title' => $config['share_title'] ?? '',
    'desc' => $config['share_desc'] ?? '',
    'url' => $config['share_link'] ?? '',
    'img' => $config['share_cover'] ?? '',
]);
?>