$(function () {
    recOpenedMenuTabs();

    //计算元素集合的总宽度
    function calSumWidth(elements) {
        var width = 0;
        $(elements).each(function () {
            width += $(this).outerWidth(true);
        });
        return width;
    }

    //滚动到指定选项卡
    function scrollToTab(element) {
        var marginLeftVal = calSumWidth($(element).prevAll()), marginRightVal = calSumWidth($(element).nextAll());
        // 可视区域非tab宽度
        var tabOuterWidth = calSumWidth($(".content-tabs").children().not(".J_menuTabs"));
        //可视区域tab宽度
        var visibleWidth = $(".content-tabs").outerWidth(true) - tabOuterWidth;
        //实际滚动宽度
        var scrollVal = 0;
        if ($(".page-tabs-content").outerWidth() < visibleWidth) {
            scrollVal = 0;
        } else if (marginRightVal <= (visibleWidth - $(element).outerWidth(true) - $(element).next().outerWidth(true))) {
            if ((visibleWidth - $(element).next().outerWidth(true)) > marginRightVal) {
                scrollVal = marginLeftVal;
                var tabElement = element;
                while ((scrollVal - $(tabElement).outerWidth()) > ($(".page-tabs-content").outerWidth() - visibleWidth)) {
                    scrollVal -= $(tabElement).prev().outerWidth();
                    tabElement = $(tabElement).prev();
                }
            }
        } else if (marginLeftVal > (visibleWidth - $(element).outerWidth(true) - $(element).prev().outerWidth(true))) {
            scrollVal = marginLeftVal - $(element).prev().outerWidth(true);
        }
        $('.page-tabs-content').animate({
            marginLeft: 0 - scrollVal + 'px'
        }, "fast");
    }

    //查看左侧隐藏的选项卡
    function scrollTabLeft() {
        var marginLeftVal = Math.abs(parseInt($('.page-tabs-content').css('margin-left')));
        // 可视区域非tab宽度
        var tabOuterWidth = calSumWidth($(".content-tabs").children().not(".J_menuTabs"));
        //可视区域tab宽度
        var visibleWidth = $(".content-tabs").outerWidth(true) - tabOuterWidth;
        //实际滚动宽度
        var scrollVal = 0;
        if ($(".page-tabs-content").width() < visibleWidth) {
            return false;
        } else {
            var tabElement = $(".J_menuTab:first");
            var offsetVal = 0;
            while ((offsetVal + $(tabElement).outerWidth(true)) <= marginLeftVal) {//找到离当前tab最近的元素
                offsetVal += $(tabElement).outerWidth(true);
                tabElement = $(tabElement).next();
            }
            offsetVal = 0;
            if (calSumWidth($(tabElement).prevAll()) > visibleWidth) {
                while ((offsetVal + $(tabElement).outerWidth(true)) < (visibleWidth) && tabElement.length > 0) {
                    offsetVal += $(tabElement).outerWidth(true);
                    tabElement = $(tabElement).prev();
                }
                scrollVal = calSumWidth($(tabElement).prevAll());
            }
        }
        $('.page-tabs-content').animate({
            marginLeft: 0 - scrollVal + 'px'
        }, "fast");
    }

    //查看右侧隐藏的选项卡
    function scrollTabRight() {
        var marginLeftVal = Math.abs(parseInt($('.page-tabs-content').css('margin-left')));
        // 可视区域非tab宽度
        var tabOuterWidth = calSumWidth($(".content-tabs").children().not(".J_menuTabs"));
        //可视区域tab宽度
        var visibleWidth = $(".content-tabs").outerWidth(true) - tabOuterWidth;
        //实际滚动宽度
        var scrollVal = 0;
        if ($(".page-tabs-content").width() < visibleWidth) {
            return false;
        } else {
            var tabElement = $(".J_menuTab:first");
            var offsetVal = 0;
            while ((offsetVal + $(tabElement).outerWidth(true)) <= marginLeftVal) {//找到离当前tab最近的元素
                offsetVal += $(tabElement).outerWidth(true);
                tabElement = $(tabElement).next();
            }
            offsetVal = 0;
            while ((offsetVal + $(tabElement).outerWidth(true)) < (visibleWidth) && tabElement.length > 0) {
                offsetVal += $(tabElement).outerWidth(true);
                tabElement = $(tabElement).next();
            }
            scrollVal = calSumWidth($(tabElement).prevAll());
            if (scrollVal > 0) {
                $('.page-tabs-content').animate({
                    marginLeft: 0 - scrollVal + 'px'
                }, "fast");
            }
        }
    }

    // 进入页面先恢复页面状态
    function recOpenedMenuTabs() {
        // 恢复topMenu激活状态
        const activeTopMenu = window.sessionStorage.getItem("activeTopMenu");
        if (activeTopMenu) {
            const topMenu = $('.rfTopMenu:eq(' + activeTopMenu + ')');
            const type = topMenu.data('type');
            $('.rfTopMenu').removeClass('open');
            if (type) {
                $('.rfTopMenu').removeClass('rfTopMenuHover');
                topMenu.addClass('rfTopMenuHover');
                setTimeout(function () {
                    $('.rfLeftMenu').addClass('hide');
                    $('.rfLeftMenu-' + type).removeClass('hide');
                });
            }
        }

        // 恢复leftMenu展开状态
        const openedLeftMenu = window.sessionStorage.getItem("openedLeftMenu");
        $('.sidebar-menu > .rfLeftMenu:eq(' + openedLeftMenu + ')').addClass('menu-open').children('.treeview-menu').show();

        // 恢复tabs激活状态
        const openedTabs = JSON.parse(window.sessionStorage.getItem("openedTabs")) || [];
        if (openedTabs.length > 0) {
            let activeTab = JSON.parse(window.sessionStorage.getItem("activeTab")) || null;
            let tabs = '',
                iframes = '';
            $(openedTabs).each(function (k, v) {
                const active = activeTab && activeTab.url === v.url;
                tabs += '<a href="javascript:;" class="' + (active ? "active " : "") + 'J_menuTab" data-id="' + v.url + '">' + v.name + '<i class="icon ion-android-close"></i></a>';

                iframes += '<iframe ' + (active ? "" : 'style="display:none"') + 'class="J_iframe" name="iframe' + v.index + '" width="100%" height="100%" src="' + v.url + '" frameborder="0" data-id="' + v.url + '" seamless></iframe>';
            });
            // 添加tabs
            $('.J_menuTabs .page-tabs-content').append(tabs);
            // 添加tabs对应的iframe
            $('.J_mainContent').find('iframe.J_iframe').hide().parents('.J_mainContent').append(iframes);

            // 滚动到activeTab
            if (activeTab) {
                $('#rftagsIndexLink').removeClass('active');
            }
        }
    }

    //通过遍历给菜单项加上data-index属性
    $(".J_menuItem").each(function (index) {
        if (!$(this).attr('data-index')) {
            $(this).attr('data-index', index);
        }
    });

    function menuItem() {
        let activeTab = null,
            openedTabs = [];

        // 获取标识数据
        var dataUrl = $(this).attr('href'),
            dataIndex = $(this).data('index'),
            menuName = $.trim($(this).text()),
            flag = true;

        if (dataUrl == undefined || $.trim(dataUrl).length == 0) return false;

        // 选项卡菜单已存在
        $('.J_menuTab').each(function () {
            if ($(this).data('id') == dataUrl) {
                if (!$(this).hasClass('active')) {
                    $(this).addClass('active').siblings('.J_menuTab').removeClass('active');
                    scrollToTab(this);
                    // 显示tab对应的内容区
                    $('.J_mainContent .J_iframe').each(function () {
                        if ($(this).data('id') == dataUrl) {
                            $(this).show().siblings('.J_iframe').hide();
                            return false;
                        }
                    });

                    // 改变当前激活的tab
                    activeTab = {index: dataIndex, name: menuName, url: dataUrl};
                    window.sessionStorage.setItem("activeTab", JSON.stringify(activeTab));  // 当前的tab
                }
                flag = false;
                return false;
            }
        });

        // 选项卡菜单不存在
        if (flag) {
            var str = '<a href="javascript:;" class="active J_menuTab" data-id="' + dataUrl + '">' + menuName + '<i class="icon ion-android-close"></i></a>';
            $('.J_menuTab').removeClass('active');

            // 添加选项卡对应的iframe
            var str1 = '<iframe class="J_iframe" name="iframe' + dataIndex + '" width="100%" height="100%" src="' + dataUrl + '" frameborder="0" data-id="' + dataUrl + '" seamless></iframe>';
            $('.J_mainContent').find('iframe.J_iframe').hide().parents('.J_mainContent').append(str1);

            // 显示loading提示并关闭菜单
            $("body").removeClass('sidebar-open');
            var loading = layer.load(2, {
                time: 10 * 1000, // 最长等待时间
            });
            $('.J_mainContent iframe:visible').on('load', function () {
                //iframe加载完成后隐藏loading提示
                layer.close(loading);
            });

            // 添加选项卡
            $('.J_menuTabs .page-tabs-content').append(str);
            scrollToTab($('.J_menuTab.active'));

            /** 将当前打开的tab页记录到sessionStorage中 **/
            if (window.sessionStorage.getItem("openedTabs")) {
                openedTabs = JSON.parse(window.sessionStorage.getItem("openedTabs"));
            }
            activeTab = {index: dataIndex, name: menuName, url: dataUrl};
            openedTabs.push(activeTab);
            window.sessionStorage.setItem("activeTab", JSON.stringify(activeTab));  // 当前的tab
            window.sessionStorage.setItem("openedTabs", JSON.stringify(openedTabs));  // 打开的的tab

            // 判断如果不开启tag标签页就关闭其他标签页
            if (config.tag != true || config.isMobile == true) {
                closeOtherTabs();
            }
        }
        return false;
    }

    // 内页打开新标签
    window.openConTab = function (that) {
        // 获取标识数据
        var dataUrl = that.attr('href'),
            dataIndex = that.data('index'),
            menuTitle = $.trim(that.data('title')),
            menuName = menuTitle.length > 0 ? menuTitle : $.trim(that.text()),
            flag = true;

        if (dataUrl == undefined || $.trim(dataUrl).length == 0) return false;

        // 选项卡菜单已存在
        $('.J_menuTab').each(function () {
            if ($(this).data('id') == dataUrl) {
                if (!$(this).hasClass('active')) {
                    $(this).addClass('active').siblings('.J_menuTab').removeClass('active');
                    scrollToTab(this);
                    // 显示tab对应的内容区
                    $('.J_mainContent .J_iframe').each(function () {
                        if ($(this).data('id') == dataUrl) {
                            $(this).show().siblings('.J_iframe').hide();
                            return false;
                        }
                    });

                    // 重新加载
                    $(this).trigger('dblclick');
                }
                flag = false;
                return false;
            }
        });

        // 选项卡菜单不存在
        if (flag) {
            var str = '<a href="javascript:;" class="active J_menuTab" data-id="' + dataUrl + '">' + menuName + '<i class="icon ion-android-close"></i></a>';
            $('.J_menuTab').removeClass('active');

            // 添加选项卡对应的iframe
            var str1 = '<iframe class="J_iframe" name="iframe' + dataIndex + '" width="100%" height="100%" src="' + dataUrl + '" frameborder="0" data-id="' + dataUrl + '" seamless></iframe>';
            $('.J_mainContent').find('iframe.J_iframe').hide().parents('.J_mainContent').append(str1);

            $("body").removeClass('sidebar-open');
            var loading = layer.load(2, {
                time: 10 * 1000, // 最长等待时间
            });
            $('.J_mainContent iframe:visible').on('load', function () {
                // iframe加载完成后隐藏loading提示
                layer.close(loading);
            });

            // 添加选项卡
            $('.J_menuTabs .page-tabs-content').append(str);
            scrollToTab($('.J_menuTab.active'));

            // 判断如果不开启tag标签页就关闭其他标签页
            if (config.tag != true || config.isMobile == true) {
                closeOtherTabs();
            }
        }
        return false;
    };

    $('.J_menuItem').on('click', menuItem);

    // 关闭选项卡菜单
    function closeTab() {
        var closeTabId = $(this).parents('.J_menuTab').data('id');
        var currentWidth = $(this).parents('.J_menuTab').width();

        /** 删除sessionStorage中的tab **/
        const closeTabIndex = $(this).parents('.J_menuTab').index() - 1; // 减掉一个固定首页tab
        let openedTabs = JSON.parse(window.sessionStorage.getItem("openedTabs")) || [];

        // 当前元素处于活动状态
        if ($(this).parents('.J_menuTab').hasClass('active')) {

            // 当前元素后面有同辈元素，使后面的一个元素处于活动状态
            if ($(this).parents('.J_menuTab').next('.J_menuTab').length) {
                // 设置activeTab为后一个
                window.sessionStorage.setItem("activeTab", JSON.stringify(openedTabs[closeTabIndex + 1]));

                var activeId = $(this).parents('.J_menuTab').next('.J_menuTab:eq(0)').data('id');
                $(this).parents('.J_menuTab').next('.J_menuTab:eq(0)').addClass('active');

                $('.J_mainContent .J_iframe').each(function () {
                    if ($(this).data('id') == activeId) {
                        $(this).show().siblings('.J_iframe').hide();
                        return false;
                    }
                });

                var marginLeftVal = parseInt($('.page-tabs-content').css('margin-left'));
                if (marginLeftVal < 0) {
                    $('.page-tabs-content').animate({
                        marginLeft: (marginLeftVal + currentWidth) + 'px'
                    }, "fast");
                }

                //  移除当前选项卡
                $(this).parents('.J_menuTab').remove();
                // 定位到当前选项卡
                $('.J_tabShowActive').on('click', scrollToTab($('.J_menuTab.active')));

                // 移除tab对应的内容区
                $('.J_mainContent .J_iframe').each(function () {
                    if ($(this).data('id') == closeTabId) {
                        $(this).remove();
                        return false;
                    }
                });
            }

            // 当前元素后面没有同辈元素，使当前元素的上一个元素处于活动状态
            if ($(this).parents('.J_menuTab').prev('.J_menuTab').length) {
                // 设置activeTab为前一个
                if (openedTabs[closeTabIndex - 1]) {
                    window.sessionStorage.setItem("activeTab", JSON.stringify(openedTabs[closeTabIndex - 1]));
                } else {
                    window.sessionStorage.removeItem("activeTab");
                }

                var activeId = $(this).parents('.J_menuTab').prev('.J_menuTab:last').data('id');
                $(this).parents('.J_menuTab').prev('.J_menuTab:last').addClass('active');
                $('.J_mainContent .J_iframe').each(function () {
                    if ($(this).data('id') == activeId) {
                        $(this).show().siblings('.J_iframe').hide();
                        return false;
                    }
                });
                //  移除当前选项卡
                $(this).parents('.J_menuTab').remove();
                //滚动到已激活的选项卡
                $('.J_tabShowActive').on('click', scrollToTab($('.J_menuTab.active')));

                // 移除tab对应的内容区
                $('.J_mainContent .J_iframe').each(function () {
                    if ($(this).data('id') == closeTabId) {
                        $(this).remove();
                        return false;
                    }
                });
            }
        }
        // 当前元素不处于活动状态
        else {
            //  移除当前选项卡
            $(this).parents('.J_menuTab').remove();

            // 移除相应tab对应的内容区
            $('.J_mainContent .J_iframe').each(function () {
                if ($(this).data('id') == closeTabId) {
                    $(this).remove();
                    return false;
                }
            });
            scrollToTab($('.J_menuTab.active'));
        }

        // 重置openedTabs
        openedTabs.splice(closeTabIndex, 1);
        window.sessionStorage.setItem("openedTabs", JSON.stringify(openedTabs));

        return false;
    }

    $('.J_menuTabs').on('click', '.J_menuTab i', closeTab);

    //关闭其他选项卡
    function closeOtherTabs() {
        $('.page-tabs-content').children("[data-id]").not(":first").not(".active").each(function () {
            $('.J_iframe[data-id="' + $(this).data('id') + '"]').remove();
            $(this).remove();
        });
        $('.page-tabs-content').css("margin-left", "0");

        // 重置openedTabs
        const activeTab = JSON.parse(window.sessionStorage.getItem("activeTab"));
        window.sessionStorage.setItem("openedTabs", JSON.stringify([activeTab]));
    }

    $('.J_tabCloseOther').on('click', closeOtherTabs);

    //滚动到已激活的选项卡
    function showActiveTab() {
        scrollToTab($('.J_menuTab.active'));
    }

    $('.J_tabShowActive').on('click', showActiveTab);


    // 点击选项卡菜单
    function activeTab() {
        if (!$(this).hasClass('active')) {
            var currentId = $(this).data('id');
            // 显示tab对应的内容区
            $('.J_mainContent .J_iframe').each(function () {
                if ($(this).data('id') == currentId) {
                    $(this).show().siblings('.J_iframe').hide();
                    return false;
                }
            });
            $(this).addClass('active').siblings('.J_menuTab').removeClass('active');
            scrollToTab(this);

            // 重置activeTab
            const openedTabs = JSON.parse(window.sessionStorage.getItem("openedTabs"));
            const activeTabIndex = $(this).index() - 1;
            if (openedTabs[activeTabIndex]) {
                window.sessionStorage.setItem("activeTab", JSON.stringify(openedTabs[activeTabIndex]));
            } else {
                window.sessionStorage.removeItem("activeTab")
            }
        }
    }

    $('.J_menuTabs').on('click', '.J_menuTab', activeTab);

    //刷新iframe
    function refreshTab() {
        var target = $('.J_iframe[data-id="' + $(this).data('id') + '"]');
        var url = target.attr('src');
        //显示loading提示
        $("body").removeClass('sidebar-open');
        var loading = layer.load(2, {
            time: 10 * 1000, // 最长等待时间
        });
        target.attr('src', url).on('load', function () {
            //关闭loading提示
            layer.close(loading);
        });
    }

    $('.J_menuTabs').on('dblclick', '.J_menuTab', refreshTab);

    // 左移按扭
    $('.J_tabLeft').on('click', scrollTabLeft);

    // 右移按扭
    $('.J_tabRight').on('click', scrollTabRight);

    // 关闭全部
    $('.J_tabCloseAll').on('click', function () {
        $('.page-tabs-content').children("[data-id]").not(":first").each(function () {
            $('.J_iframe[data-id="' + $(this).data('id') + '"]').remove();
            $(this).remove();
        });
        $('.page-tabs-content').children("[data-id]:first").each(function () {
            $('.J_iframe[data-id="' + $(this).data('id') + '"]').show();
            $(this).addClass("active");
        });
        $('.page-tabs-content').css("margin-left", "0");

        // 重置tabs
        window.sessionStorage.removeItem("activeTab");
        window.sessionStorage.removeItem("openedTabs")
    });

});
