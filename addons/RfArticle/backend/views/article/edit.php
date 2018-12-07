<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '文章管理', 'url' => AddonUrl::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>基本信息</h5>
            </div>
            <div class="ibox-content">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                        ]
                    ]); ?>
                    <?= $form->field($model, 'title')->textInput(); ?>
                    <?= $form->field($model, 'author')->textInput(); ?>
                    <?= $form->field($model, 'sort')->textInput(); ?>
                    <?= $form->field($model, 'cate_id')->dropDownList($cates, ['prompt' => '请选择分类']) ?>
                    <?= $form->field($model, 'cover')->widget(Images::className(), [
                        'config' => [
                            // 可设置自己的上传地址, 不设置则默认地址
                            // 'server' => '',
                            'pick' => [
                                'multiple' => false,
                            ],
                            // 不配置则不生成缩略图
                            'formData' => [
                                'thumb' => [
                                    [
                                        'widget' => 100,
                                        'height' => 100,
                                    ],
                                ]
                            ],
                            'chunked' => false,// 开启分片上传
                            'chunkSize' => 512 * 1024,// 分片大小
                        ]
                    ]); ?>
                    <?= $form->field($model, 'description')->textarea(); ?>
                    <?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::className()) ?>
                    <div class="form-group field-article-position">
                        <div class='col-sm-2 text-right'>
                            <label class="control-label">推荐位</label>
                        </div>
                        <div class='col-sm-10'>
                            <input type="hidden" name="Article[position]" value="">
                            <div id="article-position">
                                <?php foreach ($positionExplain as $key => $value){ ?>
                                    <label class="checkbox-inline i-checks">
                                        <input type="checkbox" name="Article[position][]" value="<?= $key?>" <?php if(\addons\RfArticle\common\models\Article::checkPosition($key, $model->position)){ ?>checked<?php } ?>> <?= $value?>
                                    </label>
                                <?php } ?>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($tags)){ ?>
                        <div class="form-group field-article-tag">
                            <div class='col-sm-2 text-right'>
                                <label class="control-label">文章标签</label>
                            </div>
                            <div class='col-sm-10'>
                                <div id="article-position">
                                    <?php foreach ($tags as $key => $item){ ?>
                                        <label class="checkbox-inline i-checks">
                                            <input type="checkbox" name="tag[]" value="<?= $item['id']?>" <?php if(!empty($item['tagMap'])){ ?>checked<?php } ?>> <?= $item['title']?>
                                        </label>
                                    <?php } ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?= $form->field($model, 'link')->textInput(); ?>
                    <?= $form->field($model, 'status')->radioList(['1' => '启用','0' => '禁用']); ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>