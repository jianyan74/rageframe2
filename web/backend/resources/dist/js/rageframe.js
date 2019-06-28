$(document).ready(function () {
    $('.sidebar-menu').tree();
    if ($(this).width() < 769) {
        config.isMobile = true;
    }

    // 触发插件菜单默认显示值
    let addonTopMenu = 1;
    $('.rfTopMenu').each(function () {
        if (parseInt($(this).data('is_addon')) === 1) {
            addonTopMenu = $(this).data('type');
        }
    });
    $('.rfLeftMenuAddon').addClass('rfLeftMenu-' + addonTopMenu);

    autoChangeMenu(true);

    // 修改颜色
    autoFontColor();
});

$(window).resize(function(){
    var leftAuto = true;
    if (config.isMobile == false) {
        leftAuto = false;
    }

    if ($(this).width() < 769) {
        config.isMobile = true;
    } else {
        config.isMobile = false;
    }

    if (config.isMobile == false && leftAuto == false) {
        autoChangeMenu();
    } else {
        autoChangeMenu(true);
    }
});

function autoFontColor() {
    $("body").find("label").each(function(i, data){
        if ($(data).find('input').length > 0) {
            $(data).attr('style', 'color:#636f7a');
        }
    })
}

function autoChangeMenu(leftAuto = false) {
    // 改变框架高度
    var mainContent = window.innerHeight - 143;
    if (config.tag != true || config.isMobile == true){
        mainContent = mainContent + 40;
    }
    $(".J_mainContent").height(mainContent);

    if (config.isMobile == true) {
        // 显示左边菜单
        $('.rfLeftMenu').removeClass('hide');
        // 隐藏tag
        $(".content-tabs").addClass('hide');
        // 显示退出
        $("#logout").removeClass('hide');
        // 隐藏头部菜单栏
        $('.rfTopMenu').each(function (i, data) {
            var type = $(this).data('type');
            if (type) {
                $(this).addClass('hide');
            }
        });

        // 增加样式
        $(".J_mainContent").addClass('rfMainContent');
        // 底部隐藏
        $(".main-footer").addClass('hide');
    } else {
        if (leftAuto == true) {
            // 隐藏左边菜单
            $('.rfLeftMenu').addClass('hide');
            // 默认菜单显示
            $('.is_default_show').removeClass('hide');
        }

        // 头部菜单栏
        $('.rfTopMenu').removeClass('hide');
        // 显示标签
        $('.content-tabs').removeClass('hide');
        // 隐藏退出
        $("#logout").addClass('hide');
        // 移除样式
        $(".J_mainContent").removeClass('rfMainContent');
        // 底部显示
        $(".main-footer").removeClass('hide');
    }

    if (config.tag != true) {
        // 隐藏tag
        $(".content-tabs").addClass('hide');
        // 显示退出
        $("#logout").removeClass('hide');
    }
}

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
$(document).on("click", ".openContab", function(e){
    parent.openConTab($(this));
    return false;
});

/* 打一个新窗口 */
$(document).on("click", ".openIframe", function(e){
    var title = $(this).data('title');
    var width = $(this).data('width');
    var height = $(this).data('height');
    var href = $(this).attr('href');

    if (title == undefined) {
        title = '基本信息';
    }

    if (width == undefined) {
        width = '80%';
    }

    if (height == undefined) {
        height = '80%';
    }

    openIframe(title, width, height, href);
    e.preventDefault();
    return false;
});

layer.config({
    extend: 'style.css', //加载您的扩展样式
});

// 打一个新窗口
function openIframe(title, width, height, content){
    layer.open({
        type: 2,
        title: title,
        shade: 0.3,
        offset: "10%",
        shadeClose : true,
        btn: ['保存', '关闭'],
        yes: function(index, layero) {
            var body = layer.getChildFrame('body', index);
            var form = body.find('#w0');
            var postUrl = form.attr('action');
            $.ajax({
                type : "post",
                url : postUrl,
                dataType : "json",
                data : form.serialize(),
                success : function(data) {
                    if (parseInt(data.code) !== 200) {
                        rfMsg(data.message);
                    } else {
                        layer.close(index);
                    }
                }
            });
        },
        btn2: function(){
            layer.closeAll();
        },
        area: [width, height],
        content: content
    });

    return false;
}

// 另外一种风格提示
function rfMsg(title) {
    layer.msg(title);
}

// 错误提示
function rfError(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "error",
        button: "确定",
    });
}

// 警告提示
function rfWarning(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "warning",
        button: "确定",
    });
}

// 普通提示
function rfAffirm(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        button: "确定",
    });
}

// 信息提示
function rfInfo(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "info",
        button: "确定",
    });
}

// 成功提示
function rfSuccess(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "success",
        button: "确定",
    });
}

// 删除提示
function rfDelete(obj, text) {
    if (!text) {
        text =  '请谨慎操作';
    }

    appConfirm("您确定要删除这条记录吗?", text, function (value){
        switch (value) {
            case "defeat":
                window.location = $(obj).attr('href');
                break;
            default:
        }
    })
}

// 二次确认提示
function rfTwiceAffirm(obj, title, text) {
    var dialogText = rfText(text);

    swal(title, {
        buttons: {
            cancel: "取消",
            defeat: '确定',
        },
        title: title,
        text: dialogText,
        // icon: "warning",
    }).then((value) => {
        switch (value) {
            case "defeat":
                window.location = $(obj).attr('href');
                break;
            default:
        }
    });
}

//删除确认提示
function appConfirm(title, text, onConfirm){
    swal(title, {
        buttons: {
            cancel: "取消",
            defeat: '确定',
        },
        title: title,
        text: text,
        icon: "warning",
    }).then(onConfirm);
}

function rfText(text) {
    if (text) {
        return text;
    }

    return '小手一抖就打开了一个框';
}