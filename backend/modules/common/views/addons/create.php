<?php

use yii\widgets\ActiveForm;
use common\helpers\ArrayHelper;
use common\helpers\Url;
use common\enums\WechatEnum;

$this->title = '设计新插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 已安装的插件</a></li>
                <li><a href="<?= Url::to(['local']) ?>"> 安装插件</a></li>
                <li class="active"><a href="<?= Url::to(['create']) ?>"> 设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane rf-auto">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'enctype' => 'multipart/form-data'
                            ],
                        ]); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'title')->textInput()->hint('显示在用户的插件列表中. 不要超过20个字符') ?>
                                <?= $form->field($model,
                                    'name')->textInput()->hint('应对应插件文件夹的名称, 系统按照此标识符查找插件定义, 只能英文和下划线组成，建议大写驼峰，例如：RfArticle') ?>
                                <?= $form->field($model, 'group')->dropDownList(ArrayHelper::map($addonsGroup, 'name',
                                    'title')) ?>
                                <?= $form->field($model, 'version')->textInput()->hint('此版本号用于插件的版本更新') ?>
                                <?= $form->field($model, 'brief_introduction')->textInput() ?>
                                <?= $form->field($model, 'description')->textarea()->hint('详细介绍插件的功能和使用方法 ') ?>
                                <?= $form->field($model, 'author')->textInput() ?>
                                <?= $form->field($model, 'is_setting')->checkbox()->hint('勾选后会开启该功能') ?>
                                <?= $form->field($model, 'is_merchant_route_map')->checkbox()->hint('开启后会将商户端的url直接映射到后台来，节省相同代码，请了解后再使用') ?>
                                <div class="hr-line-dashed"></div>
                                <?= $form->field($model, 'wechat_message')->checkboxList(WechatEnum::getMap())->hint('当前插件能够直接处理的消息类型(没有上下文的对话语境, 能直接处理消息并返回数据). 如果公众平台传递过来的消息类型不在设定的类型列表中, 那么系统将不会把此消息路由至此插件') ?>
                                <div class="alert-warning alert">
                                    注意: 关键字路由只能针对文本消息有效, 文本消息最为重要. 其他类型的消息并不能被直接理解, 多数情况需要使用文本消息来进行语境分析, 再处理其他相关消息类型<br>
                                    需要在非文字回复的插件中添加才能生效
                                </div>
                                <?= $form->field($model, 'is_rule')->checkbox()->hint('是否要在自定义插件回复编辑时添加此规则的相应的规则,勾选后会开启该功能') ?>
                                <div class="alert-warning alert">
                                    注意: 如果需要嵌入规则, 那么此插件必须能够处理文本类型消息 (WechatMessage)<br>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <?php foreach ($menuTypes as $key => $menuType) { ?>
                                    <div class="form-group desk-menu">
                                        <label class="control-label"><?= $menuType ?>菜单</label>
                                    </div>
                                    <div class="well well-sm">
                                        <div class="col-sm-12">
                                            <div class="col-md-2">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">名称</span>
                                                    <input class="form-control" name="bindings[menu][<?= $key ?>][title][]" placeholder="首页管理" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">路由</span>
                                                    <input class="form-control" name="bindings[menu][<?= $key ?>][route][]" placeholder="test/index" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">图标</span>
                                                    <input class="form-control" name="bindings[menu][<?= $key ?>][icon][]" placeholder="fa fa-wechat" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">参数</span>
                                                    <input class="form-control" name="bindings[menu][<?= $key ?>][params][]" type="text" readonly>
                                                    <span class="input-group-addon editValue" data-toggle="modal" data-target="#ajaxModalLgForAttribute">编辑</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div style="margin-left:-15px;margin-top:7px">
                                                    <a class="icon ion-android-cancel" href="javascript:void(0);" onclick="$(this).parent().parent().parent().remove()"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add">
                                            <a href="javascript:void(0);" class="m-l" onclick="addOption('menu',this, '<?= $key ?>');">添加菜单 <i class="icon ion-android-add-circle" title="添加菜单"></i></a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="hint-block">会在顶部导航菜单或者应用中心入口创建菜单列表</div>
                                <div class="hr-line-dashed"></div>
                                <?php foreach ($coverTypes as $key => $coverType) { ?>
                                    <div class="form-group desk-menu">
                                        <label class="control-label"><?= $coverType ?>入口</label>
                                    </div>
                                    <div class="well well-sm desk-menu">
                                        <div class="col-sm-12">
                                            <div class="col-md-2">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">名称</span>
                                                    <input class="form-control" name="bindings[cover][<?= $key ?>][title][]" placeholder="首页入口" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">路由</span>
                                                    <input class="form-control" name="bindings[cover][<?= $key ?>][route][]" placeholder="test/index" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">图标</span>
                                                    <input class="form-control" name="bindings[cover][<?= $key ?>][icon][]" placeholder="fa fa-wechat" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group rfAddonAddMenu">
                                                    <span class="input-group-addon">参数</span>
                                                    <input class="form-control" name="bindings[cover][<?= $key ?>][params][]" type="text" readonly>
                                                    <span class="input-group-addon editValue" data-toggle="modal" data-target="#ajaxModalLgForAttribute">编辑</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div style="margin-left:-15px;margin-top:7px">
                                                    <a class="icon ion-android-cancel" href="javascript:void(0);" onclick="$(this).parent().parent().parent().remove()"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add">
                                            <a href="javascript:;" class="m-l" onclick="addOption('cover',this, '<?= $key ?>');">添加入口 <i class="icon ion-android-add-circle" title="添加菜单"></i></a>
                                        </div>
                                    </div>
                                    <div class="help-block"></div>
                                <?php } ?>

                                <div class="hr-line-dashed"></div>
                                <?= $form->field($model,
                                    'install')->textInput()->hint('当前插件全新安装时所执行的脚本, 指定为单个的php脚本文件, 如: Install.php') ?>
                                <?= $form->field($model,
                                    'uninstall')->textInput()->hint('当前插件卸载时所执行的脚本, 指定为单个的php脚本文件, 如: UnInstall.php') ?>
                                <?= $form->field($model,
                                    'upgrade')->textInput()->hint('当前插件更新时所执行的脚本, 指定为单个的php脚本文件, 如: Upgrade.php') ?>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <div class="hr-line-dashed"></div>
                                    <button class="btn btn-primary" type="submit">保存</button>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="menuModel" type="text/html">
    <div class="col-sm-12">
        <div class="col-md-2">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">名称</span>
                <input class="form-control" name="bindings[{{type}}][title][]" placeholder="首页管理" type="text">
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">路由</span>
                <input class="form-control" name="bindings[{{type}}][route][]" placeholder="test/index" type="text">
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">图标</span>
                <input class="form-control" name="bindings[{{type}}][icon][]" placeholder="fa fa-wechat" type="text">
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">参数</span>
                <input class="form-control" name="bindings[{{type}}][params][]" type="text" readonly>
                <span class="input-group-addon editValue" data-toggle="modal" data-target="#ajaxModalLgForAttribute">编辑</span>
            </div>
        </div>
        <div class="col-md-2">
            <div style="margin-left:-15px;margin-top:7px">
                <a class="icon ion-android-cancel" href="javascript:void(0);" onclick="$(this).parent().parent().parent().remove()"></a>
            </div>
        </div>
    </div>
</script>


<script id="coverModel" type="text/html">
    <div class="col-sm-12">
        <div class="col-md-2">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">名称</span>
                <input class="form-control" name="bindings[{{type}}][{{coverType}}][title][]" placeholder="首页入口"
                       type="text">
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">路由</span>
                <input class="form-control" name="bindings[{{type}}][{{coverType}}][route][]"
                       placeholder="test/index" type="text">
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">图标</span>
                <input class="form-control" name="bindings[{{type}}][{{coverType}}][icon][]"
                       placeholder="fa fa-wechat" type="text">
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group rfAddonAddMenu">
                <span class="input-group-addon">参数</span>
                <input class="form-control" name="bindings[{{type}}][{{coverType}}][params][]" type="text" readonly>
                <span class="input-group-addon editValue" data-toggle="modal"
                      data-target="#ajaxModalLgForAttribute">编辑</span>
            </div>
        </div>
        <div class="col-md-2">
            <div style="margin-left:-15px;margin-top:7px">
                <a class="icon ion-android-cancel" href="javascript:void(0);" onclick="$(this).parent().parent().parent().remove()"></a>
            </div>
        </div>
    </div>
</script>

<!--模拟框加载 -->
<div class="modal fade" id="ajaxModalLgForAttribute" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title">参数</h4>
            </div>
            <div class="modal-body">
                <?= \common\helpers\Html::textarea('value', '', [
                    'class'=> 'form-control',
                    'id' => 'tmpValue',
                    'style' => 'height:200px',
                    'placeholder' => '例如：status:1',
                ]);?>
                <div class="help-block">
                    一行为一个k-v参数值，多个参数值用换行输入<br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button class="btn btn-primary js-confirm" data-dismiss="modal">保存</button>
            </div>
        </div>
    </div>
</div>

<script>
    function addOption(type, obj, coverType = '') {
        var html;
        var data = [];
        data.type = type;
        data.coverType = coverType;
        if (coverType.length > 0) {
            html = template('coverModel', data);
        } else {
            html = template('menuModel', data);
        }

        $(obj).parent().parent().find('.add').before(html);
    }

    // 编辑参数值
    $(document).on("click", ".editValue",function(){
        editValue = $(this).parent();
        var value = $(editValue).find('input').val();

        if (value) {
            var value = value.split(',');
            var html = '';
            console.log(value);
            for (var i = 0; i < value.length; i++) {
                if(value[i] !== ""){
                    if ((i+1) == value.length) {
                        html += value[i]
                    } else {
                        html += value[i] + "\n";
                    }
                }
            }
        }

        $('#tmpValue').val(html);
    });

    // 确定编辑参数
    $(document).on("click", ".js-confirm",function(){
        var tmpVal = $('#tmpValue').val();
        var value = tmpVal.split("\n");
        var html = '';
        for (var i = 0; i < value.length; i++) {
            if(value[i] !== "" && value[i].length > 0){
                if ((i+1) == value.length) {
                    html += value[i]
                } else {
                    html += value[i] + ",";
                }
            }
        }

        $(editValue).parent().find('input').val(html);
    });
</script>