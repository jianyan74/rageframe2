<?php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => $model->formName(),
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body" style="min-height: 630px">
        <div class="form-group">
            <div class="col-sm-2 text-right">
                <label class="control-label">不包邮地区</label>
            </div>
            <div class="col-sm-10">
                <a class="js-select-city btn btn-primary btn-sm" data-toggle="modal" data-target="#ajaxModalLgForExpress">指定地区城市</a>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2 text-right">
                <label class="control-label">已选择地区</label>
            </div>
            <div class="col-sm-10">
                <span class="js-region-info region-info"></span>
                <div class="help-block"></div>
            </div>
        </div>
        <!-- 地区选择工具 -->
        <?= \backend\widgets\area\Area::widget([
            'model' => $model,
            'form' => $form,
            'provincesName' => 'province_ids',
            'cityName' => 'city_ids',
            'areaName' => 'area_ids'
        ]) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>
<?php ActiveForm::end(); ?>