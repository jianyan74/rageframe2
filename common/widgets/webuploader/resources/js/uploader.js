$(function() {
    //初始化上传控件
    $.fn.InitMultiUploader = function(config) {
        var guid = WebUploader.Base.guid();
        var fun = function(parentObj) {
            var uploader = WebUploader.create(config);
            //当validate不通过时，会以派送错误事件的形式通知
            uploader.on('error', function(type) {
                switch (type) {
                    case 'Q_EXCEED_NUM_LIMIT':
                        rfError("上传文件数量过多！");
                        break;
                    case 'Q_EXCEED_SIZE_LIMIT':
                        rfError("文件总大小超出限制！");
                        break;
                    case 'F_EXCEED_SIZE':
                        rfError("文件大小超出限制！");
                        break;
                    case 'Q_TYPE_DENIED':
                        rfError("禁止上传该类型文件！");
                        break;
                    case 'F_DUPLICATE':
                        rfError("请勿重复上传该文件！");
                        break;
                    default:
                        rfError('错误代码：' + type);
                        break;
                }
            });

            //当有一个文件添加进来的时候
            uploader.on('fileQueued', function(file) {

                //如果是单文件上传，把旧的文件地址传过去
                if (!config.pick.multiple) {
                    //uploader.options.formData.DelFilePath = parentObj.siblings(".upload-path").val();
                }

                // 验证上传上限
                if (parentObj.parent().find('.delimg').length >= config.fileNumLimit) {
                    uploader.removeFile(file);
                    rfError("最多只能上传" + config.fileNumLimit + "个");
                } else {
                    // 创建进度条
                    addProgress(parentObj, file, uploader);
                }
            });

            //当有一批文件添加进来的时候
            uploader.on('filesQueued', function(files) {
                var listNum = parentObj.parent().find('.delimg').length;

                for (var i = 0; i < files.length; i++){
                    if (listNum >= config.fileNumLimit){
                        uploader.removeFile(files[i]);
                        rfError("最多只能上传" + config.fileNumLimit + "个");
                    }

                    listNum++;
                }
            });

            // 某个文件开始上传前触发，一个文件只会触发一次
            uploader.on('uploadStart', function(file) {
                guid = WebUploader.Base.guid();
                // 创建进度条
                addProgress(parentObj, file, uploader);
            });

            // 当某个文件的分块在发送前触发，主要用来询问是否要添加附带参数，大文件在开起分片上传的前提下此事件可能会触发多次。
            uploader.on('uploadBeforeSend', function(file, data) {
                data.guid = guid;
            });

            // 文件上传过程中创建进度条实时显示
            uploader.on('uploadProgress', function(file, percentage) {
                var progressObj = parentObj.children(".upload-progress");

                percentage = Math.floor(percentage * 100);
                // progressObj.find(".progress-bar").width(percentage + "%");
                progressObj.find(".badge").html(percentage + "%");
            });

            //当文件上传出错时触发
            uploader.on('uploadError', function(file, reason) {
                uploader.removeFile(file); //从队列中移除

                rfError("上传失败，服务器错误");
            });

            //当文件上传成功时触发
            uploader.on('uploadSuccess', function(file, data) {
                console.log(data);
                if (data.code == 200) {
                    data = data.data;
                    // 如果需要合并回调
                    if (data.merge == true)
                    {
                        $.ajax({
                            type : "post",
                            url : mergeUrl,
                            dataType : "json",
                            data: {guid : data.guid},
                            success: function(data){
                                if(data.code == 200) {
                                    data = data.data;
                                    //如果是单文件上传，则赋值相应的表单;
                                    if (config.uploadType == 'image') {
                                        addImage(parentObj, data, config);
                                    } else {
                                        addFile(parentObj, data, config);
                                    }

                                    //回调
                                    if (config.callback) {
                                        $(document).trigger(config.callback, [parentObj, data, config]);
                                    }
                                } else {
                                    rfError(data.message);
                                }
                            }
                        });
                    }
                    else
                     {
                        //如果是单文件上传，则赋值相应的表单;
                        if (config.uploadType == 'image') {
                            addImage(parentObj, data, config);
                        } else {
                            addFile(parentObj, data, config);
                        }

                        //回调
                        if (config.callback) {
                            $(document).trigger(config.callback, [parentObj, data, config]);
                        }
                    }

                } else {
                    rfError(data.message);
                }

                uploader.removeFile(file); //从队列中移除
            });

            //不管成功或者失败，文件上传完成时触发
            uploader.on('uploadComplete', function(file) {
                var progressObj = parentObj.children(".upload-progress");
                //如果队列为空，则移除进度条
                if (uploader.getStats().queueNum == 0) {
                    progressObj.remove();
                    parentObj.css({'margin-bottom':"0"});
                }
            });
        };

        return $(this).each(function() {
            fun($(this));
        });
    };

    // 创建进度条
    function addProgress(parentObj, file, uploader) {
        //防止重复创建
        if (parentObj.children(".upload-progress").length == 0){
            //创建进度条
            var fileProgressObj = $('<div class="upload-progress" style="width: 110px"></div>').appendTo(parentObj);
            $('<span class="badge bg-green">0%</span>').appendTo(fileProgressObj);
            parentObj.css({'margin-bottom':"25px"});
            var progressCancel = $('<a style="color: inherit;padding-left: 20px;cursor:pointer">取消</a>').appendTo(fileProgressObj);
            //绑定点击事件
            progressCancel.click(function() {
                uploader.cancelFile(file);
                fileProgressObj.remove();
                parentObj.css({'margin-bottom':"0"});
            });
        }
    }

    // 添加图片
    function addImage(parentObj, data, config){
        console.log(parentObj);
        var multiple = config.pick.multiple;
        var boxId = parentObj.parent().attr('data-boxId');
        var name = parentObj.parent().attr('data-name');
        var newLi = $('<li>'
            + '<input type="hidden" name="'+ name +'" value="'+ data.url +'" />'
            + '<div class="img-box">'
            + '<a data-fancybox="rfUploadImg" href="'+ data.url +'">'
            + '<div class="backgroundCover" style="background-image: url(' + data.url + ');height: 110px"/>'
            + '</a>'
            + '<i class="delimg" data-multiple="'+ multiple +'"></i>'
            + '</div>'
            + '</li>');

        // 判断是否是多图上传
        if (multiple == 'false' || multiple == false){
            parentObj.hide();
        }

        // 查找文本框并移除
        parentObj.parent().find('#'+boxId).remove();
        parentObj.before(newLi);
    }

    // 添加文件
    function addFile(parentObj, data, config){
        var multiple = config.pick.multiple;
        var boxId = parentObj.parent().attr('data-boxId');
        var name = parentObj.parent().attr('data-name');

        var arr = data.url.split('.');
        var newLi = $('<li>'
            + '<input type="hidden" name="'+ name +'" value="'+ data.url +'" />'
            + '<div class="img-box">'
            + '<i class="fa fa-file"></i>'
            + '<i> .'+ arr[arr.length - 1] +'</i>'
            + '<i class="delimg" data-multiple="'+ multiple +'"></i>'
            + '</div>'
            + '</li>');

        // 判断是否是多文件上传
        if (multiple == 'false' || multiple == false){
            parentObj.hide();
        }

        // 查找文本框并移除
        parentObj.parent().find('#'+boxId).remove();
        parentObj.before(newLi);
    }

    // 删除图片节点
    $(document).on("click", ".delimg", function() {
        var parentObj = $(this).parent().parent();
        var multiple =  $(this).attr('data-multiple');
        var name = $(this).parent().parent().parent().attr('data-name');
        var boxId = $(this).parent().parent().parent().attr('data-boxId');

        if (multiple == true) {
            name = name.substring(0,name.length-2);
        }

        var input = '<input type="hidden" name="' + name + '" value="" id="'+boxId +'"/>';

        // 判断是否是多图上传
        if (multiple == 'false' || multiple == false) {
            //增加值为空的隐藏域
            $(this).parent().parent().parent().append(input);
            //显示上传图片按钮
            $(this).parent().parent().parent().find("li").show();
        } else {
            // 增加值为空的隐藏域
            var length = $(this).parent().parent().parent().find('li').length;
            if (length == 2) {
                $(this).parent().parent().parent().append(input);
            }
        }

        parentObj.remove();
    });

    // 图片/文件选择
    $(document).on("click",".rfAttachmentActive",function() {
        if (!$(this).hasClass('active')){
            $(this).addClass('active');
        }else{
            $(this).removeClass('active');
        }
    });

    // 上传事件
    $(document).on("click",".uploadWebuploader",function() {
        var boxid = $(this).parent().parent('ul').attr('id');
        $('.upload-album-' + boxid + ' input').trigger('click');
    });

    // 移除图像蒙层
    $('.photo-list').mouseleave(function(e){
        $(e.currentTarget).parent().find('.halfOpacityBlackBG').hide();
    });

    // 移除文件蒙层
    $('.file-list').mouseleave(function(e){
        $(e.currentTarget).parent().find('.halfOpacityBlackBG').hide();
    });

});