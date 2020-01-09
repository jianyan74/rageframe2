<div class="btn btn-primary selector-upload-album" id="<?= $boxId; ?>">立即上传</div>
<!--隐藏上传组件-->
<div class="hidden" id="upload-<?= $boxId; ?>">
    <div class="upload-album-<?= $boxId; ?>"></div>
</div>

<script>
    var boxId = "<?= $boxId; ?>";
    // 触发上传
    $(document).on("click", ".selector-upload-album",function(e){
        let boxId = $(this).attr('id');
        $('#upload-' + boxId + ' .webuploader-container input').trigger('click');
    });

    // 上传成功
    $(document).on('upload-success-' + boxId, function(e, data, config){
        // 判断是否已经存在文件
        oldData = $('#rfAttachmentList li').find('.' + data.id);
        if (oldData.length > 0) {
            tmpLi = oldData.parent();
            oldData.parent().remove();
            $('#rfAttachmentList').prepend(tmpLi);
            return;
        }

        let allData = [];
        let tmpData = [];
        tmpData['id'] = data.id;
        tmpData['base_url'] = data.url;
        tmpData['name'] = data.name;
        tmpData['upload_type'] = data.upload_type;
        tmpData['size'] = data.formatter_size;
        tmpData['md5'] = data.md5;
        allData.push(tmpData);

        let htmlData = [];
        htmlData['data'] = allData;
        let html = template('rfAttachmentlistModel', htmlData);
        // 渲染添加数据
        if ($('#rfAttachmentList li').length >= 10) {
            $('#rfAttachmentList li:last-child').remove();
        }
        $('#rfAttachmentList').prepend(html);
    });

    // 上传失败
    $(document).on('upload-error-' + boxId, function(e, file, reason, uploader, config){
        uploader.removeFile(file); //从队列中移除
        rfError("上传失败，服务器错误");
    });
</script>