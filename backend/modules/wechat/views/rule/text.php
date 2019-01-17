<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">回复内容</h4>
    </div>
    <div class="box-body">
        <div class="col-lg-12">
            <?= $form->field($moduleModel, 'content')->textarea() ?>
        </div>
        <div class="form-group">　
            <div class="col-sm-12 text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>