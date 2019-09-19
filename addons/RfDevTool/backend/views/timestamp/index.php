<?php

use common\helpers\Html;

$this->title = '时间戳转换';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-body">
                <div class="col-sm-6">
                    <div class="form-group field-menu-title required">
                        <span>现在时间</span>：<span id="time"></span><br>
                        <span>现在时间戳</span>：<span id="timestamp"></span>
                    </div>
                    <div class="input-group m-b">
                        <input type="text" class="form-control t1" name="timestamp" placeholder="时间戳">
                        <span class="input-group-btn" title="立即点击"><button class="btn btn-white transform1">时间戳转换日期</button></span>
                        <input type="text" class="form-control d1" name="datetime" placeholder="日期" readonly>
                    </div>
                    <div class="input-group m-b">
                        <input type="text" class="form-control d2" name="timestamp" placeholder="日期">
                        <span class="input-group-btn"><button class="btn btn-white transform2">日期转换时间戳</button></span>
                        <input type="text" class="form-control t2" name="datetime" placeholder="时间戳" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var date = new Date();
        var timestamp = parseInt(date.getTime() / 1000);
        $('.t1').val(timestamp);
        $('.d1').val(timestampToTime(timestamp));
        $('.d2').val(timestampToTime(timestamp));
        $('.t2').val(timestamp);

        setTime();
        setInterval(setTime, 1000);
    });

    $('.transform1').click(function () {
        var val = $('.t1').val();
        if (val.length === 0) {
            rfMsg('请填写内容');
            return;
        }

        $('.d1').val(timestampToTime(val));
    });

    $('.transform2').click(function () {
        var val = $('.d2').val();
        if (val.length === 0) {
            rfMsg('请填写内容');
            return;
        }

        $('.t2').val(timeToTimestamp(val));
    });

    function setTime() {
        $("#timestamp").text(timeToTimestamp());
        $("#time").text(timestampToTime(timeToTimestamp()));
    }

    function timestampToTime(timestamp) {
        var date = new Date(timestamp * 1000), str = '';//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        str += date.getFullYear() + '-'; // 获取当前年份
        str += (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
        str += (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
        str += (date.getHours() < 10 ? '0' + (date.getHours()) : date.getHours()) + ':';
        str += (date.getMinutes() < 10 ? '0' + (date.getMinutes()) : date.getMinutes()) + ':';
        str += (date.getSeconds() < 10 ? '0' + (date.getSeconds()) : date.getSeconds());
        return str;
    }

    function timeToTimestamp(time = '') {
        var date;
        if (time.length > 0) {
            date = new Date(time);
        } else {
            date = new Date();
        }

        return parseInt(date.getTime() / 1000);
    }
</script>