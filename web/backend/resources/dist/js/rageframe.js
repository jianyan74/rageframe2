$(document).ready(function () {
    $('.sidebar-menu').tree();

    if ($(this).width() < 769) {
        $(".J_mainContent").addClass('rfMainContent');
    }

    $(".J_mainContent").height(window.innerHeight-143);
});

$(window).resize(function(){
    // 判断框架高度
    $(".J_mainContent").height(window.innerHeight-143);
    // 隐藏菜单
    if ($(this).width() < 769) {
        $(".J_mainContent").addClass('rfMainContent');
        $('.rfTopMenu').each(function (i, data) {
            var type = $(this).data('type');
            if (type) {
                $(this).addClass('hide');
                $('.rfLeftMenu-' + type).removeClass('hide');
            }
        })
    } else {
        $(".J_mainContent").removeClass('rfMainContent');
        $('.rfTopMenu').each(function (i, data) {
            var type = $(this).data('type');
            $(this).removeClass('hide');
            if (type && !$(this).hasClass('rfTopMenuHover')) {
                $('.rfLeftMenu-' + type).addClass('hide');
            }
        });
    }
});

/* 导航标签切换 */
$('.rfTopMenu').click(function(){
    var type = $(this).data('type');
    $('.rfTopMenu').removeClass('open');
    if (type) {
        $('.rfTopMenu').removeClass('rfTopMenuHover');
        $('.rfLeftMenu').addClass('hide');
        $('.rfLeftMenu-' + type).removeClass('hide');
        $(this).addClass('rfTopMenuHover');
    }
});

/* 提示报错弹出框配置 */
toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

/* 在顶部导航栏打开tab */
$(".openContab").click(function(){
    parent.openConTab($(this));
    return false;
});

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
        text =  '请谨慎操作';
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