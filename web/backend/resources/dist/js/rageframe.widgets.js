
let echartsList = [];

// ----------------------------------- 文件上传 ----------------------------------- //

// 显示蒙版
$(document).on("mouseenter", ".upload-box", function (e) {
    var obj = $(e.currentTarget);
    if (!obj.is(":hidden")) {
        obj.parent().find('.befor-upload').removeClass('hide');
    }
});

// 移除文件蒙层
$(document).on("mouseleave", ".upload-list", function (e) {
    $(e.currentTarget).parent().find('.befor-upload').addClass('hide');
});

// 图片裁剪触发上传
$(document).on("click", ".crop-upload", function (e) {
    if (e.target == this) {
        let boxId = $(this).parent().parent().parent().attr('data-boxId');
        if (boxId === undefined) {
            boxId = $(this).parent().attr('data-boxId');
        }

        $('#crop-upload-' + boxId + ' a').trigger('click');
    }
});

// 触发上传
$(document).on("click", ".upload-box", function (e) {
    if (e.target == this) {
        let boxId = $(this).parent().attr('data-boxId');
        $('#upload-' + boxId + ' .webuploader-container input').trigger('click');
    }
});

// 触发上传2
$(document).on("click", ".upload-box-immediately", function () {
    let boxId = $(this).parent().parent().parent().attr('data-boxId');
    $('#upload-' + boxId + ' .webuploader-container input').trigger('click');
});

// 初始化上传
$(function () {
    //初始化上传控件
    $.fn.InitMultiUploader = function (config) {
        //待上传文件的md5值（key为file id）
        var md5 = {};
        var filesGuid = {};
        var uploadProgress = {};
        let guid = WebUploader.Base.guid();
        let fun = function (parentObj) {
            let uploader = WebUploader.create(config);
            //当validate不通过时，会以派送错误事件的形式通知
            uploader.on('error', function (type) {
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
            uploader.on('fileQueued', function (file) {
                $(document).trigger('upload-file-queued-' + config.boxId, [file, uploader, config]);
            });

            //当有一批文件添加进来的时候
            uploader.on('filesQueued', function (files) {
                for (let i = 0; i < files.length; i++) {
                    md5File(files[i], uploader, config);
                }

                $(document).trigger('upload-files-queued-' + config.boxId, [files, uploader, config]);
            });

            // 某个文件开始上传前触发，一个文件只会触发一次
            uploader.on('uploadStart', function (file) {
                uploader.options.formData.md5 = md5[file.id];

                // 创建进度条
                $(document).trigger('upload-create-progress-' + config.boxId, [file, uploader, config]);
            });

            // 当某个文件的分块在发送前触发，主要用来询问是否要添加附带参数，大文件在开起分片上传的前提下此事件可能会触发多次。
            uploader.on('uploadBeforeSend', function (object, data) {
                // 给与唯一数
                if (filesGuid[object.file.id] === undefined) {
                    filesGuid[object.file.id] = WebUploader.Base.guid();
                }

                data.md5 = md5[object.file.id];
                data.guid = filesGuid[object.file.id];
            });

            // 文件上传过程中创建进度条实时显示
            uploader.on('uploadProgress', function (file, percentage) {
                // 进入进度条库
                uploadProgress[file.id] = Math.floor(percentage * 100);
                // 实时进度条
                $(document).trigger('upload-progress-' + config.boxId, [file, percentage, config]);
            });

            //当文件上传出错时触发
            uploader.on('uploadError', function (file, reason) {
                // 触发失败回调
                $(document).trigger('upload-error-' + config.boxId, [file, reason, uploader, config]);
            });

            //当文件上传成功时触发
            uploader.on('uploadSuccess', function (file, data) {

                console.log(uploadProgress);
                console.log(data);
                // 阿里js直传
                if (config.formData.drive == 'oss-js') {
                    getOssPathUrl(data, config);
                    return;
                }

                if (parseInt(data.code) === 200) {
                    data = data.data;
                    // 如果需要合并回调
                    if (data.merge == true) {
                        $.ajax({
                            type: "post",
                            url: config.mergeUrl,
                            dataType: "json",
                            data: {guid: data.guid},
                            success: function (data) {
                                if (data.code == 200) {
                                    data = data.data;
                                    // 触发回调
                                    $(document).trigger('upload-success-' + config.boxId, [data, config]);
                                } else {
                                    rfError(data.message);
                                }
                            }
                        });
                    } else {
                        // 触发主动回调
                        $(document).trigger('upload-success-' + config.boxId, [data, config]);
                        // 被动回调
                        if (config.callback) {
                            $(document).trigger(config.callback, [data, config]);
                        }
                    }
                } else {
                    rfError(data.message);
                }

                uploader.removeFile(file); //从队列中移除
            });

            //不管成功或者失败，文件上传完成时触发
            uploader.on('uploadComplete', function (file) {
                let num = uploader.getStats().queueNum;
                $(document).trigger('upload-complete-' + config.boxId, [file, num, config, uploadProgress]);
            });
        };

        // 校验md5
        function md5File(file, uploader, config) {
            // 接管的直接上传跳过验证
            if (config.independentUrl == true) {
                $(document).trigger('md5Verify-create-progress-' + config.boxId, [file, uploader, config, '0%']);
                // 开始上传
                uploader.upload(file);
                return;
            }

            // 创建进度条默认验证中
            $(document).trigger('md5Verify-create-progress-' + config.boxId, [file, uploader, config]);
            // 加入进度条为-1
            uploadProgress[file.id] = -1;

            //获取文件MD5值
            uploader.md5File(file).progress(function (percentage) {
                console.log(percentage);
            })
                .then(function (val) {
                    // 完成
                    md5[file.id] = val;
                    // 完成进度条为-2
                    uploadProgress[file.id] = -2;

                    $.ajax({
                        type: "post",
                        url: config.verifyMd5Url,
                        dataType: "json",
                        data: {md5: md5[file.id]},
                        success: function (data) {
                            if (parseInt(data.code) === 200) {
                                //跳过如果存在则跳过
                                uploader.removeFile(file);

                                data = data.data;
                                // 触发完成验证(和完成上传一样)
                                $(document).trigger('upload-complete-' + config.boxId, [file, 0, config, uploadProgress]);
                                // 触发回调
                                $(document).trigger('upload-success-' + config.boxId, [data, config]);
                                // 被动回调
                                if (config.callback) {
                                    $(document).trigger(config.callback, [ossData, config]);
                                }
                            } else {
                                // rfError(data.message);
                                $(document).trigger('md5Verify-create-progress-' + config.boxId, [file, uploader, config, '0%']);
                                // 开始上传
                                uploader.upload(file);
                            }
                        }
                    });
                });
        }

        // 获取oss js直传的文件信息
        function getOssPathUrl(data, config) {
            var url = $(data._raw).find("Location").text();
            $.ajax({
                type: "post",
                url: config.getOssPathUrl,
                dataType: "json",
                data: {url: url, type: config.type},
                success: function (data) {
                    if (parseInt(data.code) === 200) {
                        data = data.data;
                        console.log(data);
                        // 触发回调
                        $(document).trigger('upload-success-' + config.boxId, [data, config]);
                        // 被动回调
                        if (config.callback) {
                            $(document).trigger(config.callback, [ossData, config]);
                        }
                    } else {
                        rfError(data.message);
                    }
                }
            });
        }

        return $(this).each(function () {
            fun($(this));
        });
    };

    // 图片/文件选择
    $(document).on("click", ".mailbox-attachment-icon", function (e) {
        if (!$(this).parent().hasClass('active')) {
            // 判断是否多选
            if ($('#rfAttachmentList').data('multiple') != true) {
                $('#rfAttachmentList .active').each(function (i, data) {
                    $(data).removeClass('active');
                });
            }

            $(this).parent().addClass('active');
        } else {
            $(this).parent().removeClass('active');
        }
    });
});

// ----------------------------------- 切图上传 ----------------------------------- //

$(document).on("click", ".avatar-btns span", function (e) {
    var method = $(this).data('method');
    var option = $(this).data('option');

    $('#tailoringImg').cropper(method, option);
});

//换向
var flagX = true;
$(document).on("click", ".cropper-scaleX", function (e) {
    if (flagX) {
        $('#tailoringImg').cropper("scaleX", -1);
        flagX = false;
    } else {
        $('#tailoringImg').cropper("scaleX", 1);
        flagX = true;
    }
});

//图像上传
function selectImg(file) {
    var maxSize = 1024 * 5;// 5M
    if (!file.files || !file.files[0]) {
        return;
    }

    if (!file.files[0].type.match(/image.*/)) {
        rfError('请选择正确的图片!');
        return;
    }

    var size = file.files[0].size / 1024;
    if (size > maxSize) {
        rfError('图片过大，请重新选择!');
        return;
    }

    var reader = new FileReader();
    reader.onload = function (evt) {
        var replaceSrc = evt.target.result;
        //更换cropper的图片
        $('#tailoringImg').cropper('replace', replaceSrc, false);//默认false，适应高度，不失真
    };

    reader.readAsDataURL(file.files[0]);
}

// ----------------------------------- 地图控件 ----------------------------------- //

// 地图编辑
$(document).on("click", ".map-edit", function (e) {
    var parent = $(this).parent().parent().parent();
    var url = parent.find('.rfEditMap').attr('href');
    var lng = parent.find('.mapLng').val();
    var lat = parent.find('.mapLat').val();

    url = url + "&lng=" + lng;
    url = url + "&lat=" + lat;
    parent.find('.rfEditMap').attr('href', url);
    parent.find('.rfEditMap').trigger('click')
});

// 地图选择
$(document).on("click", ".map-select", function (e) {
    var parent = $(this).parent().parent().parent();
    var url = parent.find('.rfSelectMap').attr('href');
    var lng = parent.find('.mapLng').val();
    var lat = parent.find('.mapLat').val();
    url = url + "&lng=" + lng;
    url = url + "&lat=" + lat;
    parent.find('.rfSelectMap').attr('href', url);
    parent.find('.rfSelectMap').trigger('click')
});