<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>回复内容</h5>
    </div>
    <div class="ibox-content">
        <div class="col-sm-12">
            <?= $form->field($moduleModel, 'attachment_id')->dropDownList(\common\helpers\ArrayHelper::map(\common\models\wechat\AttachmentNews::getList(), 'attachment_id', 'title')) ?>
            <div class="hr-line-dashed"></div>
        </div>
        <div class="form-group">　
            <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-primary" type="submit">保存内容</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>