<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>回复内容</h5>
    </div>
    <div class="ibox-content">
        <div class="col-sm-12">
            <?= $form->field($moduleModel, 'content')->textarea() ?>
        </div>
        <div class="form-group">　
            <div class="col-sm-12 text-center">
                <div class="hr-line-dashed"></div>
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>