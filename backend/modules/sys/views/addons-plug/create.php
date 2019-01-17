<?php
use yii\widgets\ActiveForm;
use common\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = '设计新插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['uninstall'])?>"> 已安装的插件</a></li>
                <li><a href="<?= Url::to(['install'])?>"> 安装插件</a></li>
                <li class="active"><a href="<?= Url::to(['create'])?>"> 设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane rf-auto">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'enctype' => 'multipart/form-data'
                            ]
                        ]); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'title')->textInput()->hint('模块的名称, 显示在用户的模块列表中. 不要超过20个字符') ?>
                                <?= $form->field($model, 'name')->textInput()->hint('模块标识符, 应对应模块文件夹的名称, 系统按照此标识符查找模块定义, 只能英文和下划线组成，建议大写驼峰，例如：RfArticle') ?>
                                <?= $form->field($model, 'group')->dropDownList(ArrayHelper::map($addonsGroup,'name','title')) ?>
                                <?= $form->field($model, 'version')->textInput()->hint('模块当前版本, 此版本号用于模块的版本更新')?>
                                <?= $form->field($model, 'brief_introduction')->textInput() ?>
                                <?= $form->field($model, 'description')->textarea()->hint('模块详细描述, 详细介绍模块的功能和使用方法 ')?>
                                <?= $form->field($model, 'author')->textInput()?>
                                <?= $form->field($model, 'is_mini_program')->checkbox()->hint('勾选后将会生成api目录') ?>
                                <?= $form->field($model, 'is_setting')->checkbox()->hint('勾选后会创建全局配置功能') ?>
                                <?= $form->field($model, 'is_hook')->checkbox()->hint('勾选后会创建对应的钩子方法') ?>
                                <div class="hr-line-dashed"></div>
                                <?= $form->field($model, 'wechat_message')->checkboxList(\common\models\wechat\Account::$typeExplanation)->hint('当前模块能够直接处理的消息类型(没有上下文的对话语境, 能直接处理消息并返回数据). 如果公众平台传递过来的消息类型不在设定的类型列表中, 那么系统将不会把此消息路由至此模块')?>
                                <div class="alert-warning alert">
                                    注意: 关键字路由只能针对文本消息有效, 文本消息最为重要. 其他类型的消息并不能被直接理解, 多数情况需要使用文本消息来进行语境分析, 再处理其他相关消息类型<br>
                                    需要在非文字回复的模块中添加才能生效
                                </div>
                                <?= $form->field($model, 'is_rule')->checkbox()->hint('是否要在自定义模块回复编辑时添加此规则的相应的规则 ') ?>
                                <div class="alert-warning alert">
                                    注意: 如果需要嵌入规则, 那么此模块必须能够处理文本类型消息 (WechatMessage)<br>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group desk-menu">
                                    <label class="control-label">前台(含api/微信)导航入口菜单</label>
                                </div>
                                <div class="well well-sm desk-menu">
                                    <div class="col-sm-12">
                                        <div class="col-md-3">
                                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                                <span class="input-group-addon">菜单名称</span>
                                                <input class="form-control" name="bindings[cover][title][]" placeholder="例如:首页入口" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                                <span class="input-group-addon">菜单路由</span>
                                                <input class="form-control" name="bindings[cover][route][]" placeholder="例如:test/index" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                                <span class="input-group-addon">菜单图标</span>
                                                <input class="form-control" name="bindings[cover][icon][]" placeholder="例如:fa fa-wechat" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-left:-15px;margin-top:7px">
                                                <a href="javascript:;" onclick="$(this).parent().parent().parent().remove()" class="fa fa-times-circle" title="删除此菜单"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add">
                                        <a href="javascript:;" onclick="addOption('cover',this);">添加菜单 <i class="fa fa-plus-circle" title="添加菜单"></i></a><br>
                                        前台(含api/微信)导航入口菜单,点击后进入系统中对应的功能..
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">后台管理中心导航菜单</label>
                                </div>
                                <div class="well well-sm">
                                    <div class="col-sm-12">
                                        <div class="col-md-3">
                                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                                <span class="input-group-addon">菜单名称</span>
                                                <input class="form-control" name="bindings[menu][title][]" placeholder="例如:首页管理" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                                <span class="input-group-addon">菜单路由</span>
                                                <input class="form-control" name="bindings[menu][route][]" placeholder="例如:test/index" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                                <span class="input-group-addon">菜单图标</span>
                                                <input class="form-control" name="bindings[menu][icon][]" placeholder="例如:fa fa-wechat" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-left:-15px;margin-top:7px">
                                                <a href="javascript:;" onclick="$(this).parent().parent().parent().remove()" class="fa fa-times-circle" title="删除此菜单"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add">
                                        <a href="javascript:;" onclick="addOption('menu',this);">添加菜单 <i class="fa fa-plus-circle" title="添加菜单"></i></a><br>
                                        后台管理中心导航菜单将会在管理中心生成一个导航入口(管理后台操作), 用于对模块定义的内容进行管理.
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <?= $form->field($model, 'install')->textInput()->hint('当前模块全新安装时所执行的脚本, 指定为单个的php脚本文件, 如: install.php')?>
                                <?= $form->field($model, 'uninstall')->textInput()->hint('当前模块卸载时所执行的脚本, 指定为单个的php脚本文件, 如: uninstall.php')?>
                                <?= $form->field($model, 'upgrade')->textInput()->hint('当前模块更新时所执行的脚本, 指定为单个的php脚本文件, 如: upgrade.php')?>
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
        <div class="col-md-3">
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                <span class="input-group-addon">菜单名称</span>
                <input class="form-control" name="bindings[{{type}}][title][]" placeholder="例如:首页管理/入口" type="text">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                <span class="input-group-addon">菜单路由</span>
                <input class="form-control" name="bindings[{{type}}][route][]" placeholder="例如:test/index" type="text">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                <span class="input-group-addon">菜单图标</span>
                <input class="form-control" name="bindings[{{type}}][icon][]" placeholder="例如:fa fa-wechat" type="text">
            </div>
        </div>
        <div class="col-md-3">
            <div style="margin-left:-15px;margin-top:7px">
                <a href="javascript:;" onclick="$(this).parent().parent().parent().remove()" class="fa fa-times-circle" title="删除此菜单"></a>
            </div>
        </div>
    </div>
</script>
<script>
    function addOption(type,obj) {
        var data = [];
        data.type = type;
        var html = template('menuModel',data);
        $(obj).parent().parent().find('.add').before(html);
    }

    function hideInit(){
        $('.field-addons-wxapp_support').hide();
    }

    hideInit();
</script>