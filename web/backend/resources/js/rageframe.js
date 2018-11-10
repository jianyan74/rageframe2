// 错误提示
function rfError(title, text) {
    var dialogText = rfText(text);
    swal({
        title : title,
        text  : dialogText,
        type  : "error"
    })
}

// 警告提示
function rfWarning(title, text) {
    var dialogText = rfText(text);
    swal({
        title : title,
        text  : dialogText,
        type  : "warning"
    })
}

// 普通提示
function rfAffirm(title, text) {
    var dialogText = rfText(text);
    swal({
        title : title,
        text  : dialogText
    })
}

// 信息提示
function rfInfo(title, text) {
    var dialogText = rfText(text);
    swal({
        title : title,
        text  : dialogText,
        type  : "info"
    })
}

// 成功提示
function rfSuccess(title, text) {
    var dialogText = rfText(text);
    swal({
        title : title,
        text  : dialogText,
        type  : "success"
    })
}

// 删除提示
function rfDelete(obj, text) {
    if (!text) {
        text =  '删除后将无法恢复，请谨慎操作！';
    }

    appConfirm("您确定要删除这条信息吗?", text, function (){
        var link = $(obj).attr('href');
        window.location = link;
    })
}

// 二次确认提示
function rfTwiceAffirm(obj, title, text) {
    var dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        showCancelButton: true,
        confirmButtonText: "确认",
        confirmButtonColor: "#DD6B55",
        cancelButtonText: '取消',
        closeOnConfirm: true
    }, function (){
        var link = $(obj).attr('href');
       window.location = link;
    });
}

//删除确认提示
function appConfirm(title, text, onConfirm){
	swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: "删除",
        confirmButtonColor: "#DD6B55",
        cancelButtonText: '取消',
        closeOnConfirm: true
  }, onConfirm);
}

function rfText(text) {

    if (text) {
        return text;
    } else {
        return '小手一抖就打开了一个框';
    }
}

// 复选框
$(".i-checks").iCheck({
    checkboxClass:"icheckbox_square-green",
    radioClass:"iradio_square-green"
});

var closeAlert = (function() {
    var timer;
    var i = 0;
    var closeTime = $('.rfCloseTime').text();

    function change(tar) {
        i++;
        // console.log(i);
        if (i > tar) {
            clearTimeout(timer);
            $('.alert').children('.close').click();
            return false;
        }
        timer = setTimeout(function() {
            closeTime--;
            $('.rfCloseTime').text(closeTime);
            change(tar)
        }, 1000)
    }
    return change;
})();

// 倒计时
function closeInterval() {
    var closeTime = $('.rfCloseTime').text();
    closeTime = parseInt(closeTime);
    closeAlert(closeTime);
}

// 大图显示
$("[data-fancybox]").fancybox({
    // Options will go here
    toolbar  : true,//工具栏
    buttons : [
        'slideShow',
        // 'fullScreen',
        'thumbs',
        // 'download',
        // 'share',
        'close'
    ]
});

// 导航标签切换
$('.navbar-top-menu').click(function(){
    var type = $(this).attr('menu-type');
    $('.navbar-top-menu').removeClass('navbar-top-menu-hover');
    $('.navbar-left-menu').hide();
    $('.navbar-left-menu-' + type).show();

    $(this).addClass('navbar-top-menu-hover');
});


// 在顶部导航栏打开tab
$(".openContab").click(function(){
    parent.openConTab($(this));
    return false;
});