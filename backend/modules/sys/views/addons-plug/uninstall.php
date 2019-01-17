<?php
use yii\helpers\Url;
use common\helpers\AddonHelper;

$this->title = '已安装的插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['uninstall'])?>">已安装的插件</a></li>
                <li><a href="<?= Url::to(['install'])?>">安装插件</a></li>
                <li><a href="<?= Url::to(['create'])?>">设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <p><input type="text" class="form-control query" placeholder="请输入您要查找的内容..." id="all"></p>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>图标</th>
                            <th>模块名称</th>
                            <th>版本</th>
                            <th>作者</th>
                            <th>功能支持</th>
                            <th>简介</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="listAddons">
                        <?php foreach ($list as $key => $vo){ ?>
                            <tr id ="<?= $vo['id'] ?>">
                                <td class="feed-element">
                                    <img alt="image" class="img-rounded m-t-xs img-responsive" src="<?= AddonHelper::getAddonIcon($vo['name']); ?>" width="64" height="64">
                                </td>
                                <td>
                                    <h5><?= $vo['title'] ?></h5>
                                    <small>标识 : <?= $vo['name'] ?></small>
                                </td>
                                <td><?= $vo['version'] ?></td>
                                <td><?= $vo['author'] ?></td>
                                <td>
                                    <?php if ($vo['is_mini_program'] == true){ ?>
                                        <span class="label label-info">Api/小程序</span>
                                    <?php } ?>
                                    <?php if ($vo['is_setting'] == true){ ?>
                                        <span class="label label-info">全局设置</span>
                                    <?php } ?>
                                    <?php if ($vo['is_rule'] == true){ ?>
                                        <span class="label label-info">嵌入规则</span>
                                    <?php } ?>
                                    <?php if ($vo['is_hook'] == true){ ?>
                                        <span class="label label-info">钩子</span>
                                    <?php } ?>
                                </td>
                                <td><?= $vo['brief_introduction'] ?> <a href="javascript:void(0);" class="show-description">详细介绍</a></td>
                                <td>
                                    <a href="<?= Url::to(['upgrade-config','name' => $vo['name']])?>" onclick="rfTwiceAffirm(this, '确认更新配置吗？', '会重载最新模块的配置和权限, 更新后权限需要重新授权角色');return false;"><span class="btn btn-primary btn-sm">更新配置</span></a>
                                    <a href="<?= Url::to(['upgrade','name' => $vo['name']])?>" onclick="rfTwiceAffirm(this, '确认更新数据吗？', '会执行更新数据库字段升级等功能');return false;"><span class="btn btn-primary btn-sm">更新数据</span></a>
                                    <a href="<?= Url::to(['ajax-edit','id' => $vo['id']])?>" data-toggle='modal' data-target='#ajaxModal'><span class="btn btn-primary btn-sm">编辑</span></a>
                                    <?= \common\helpers\HtmlHelper::status($vo['status']) ?>
                                    <a href="<?= Url::to(['uninstall','name' => $vo['name']])?>" data-method="post"><span class="btn btn-warning btn-sm">卸载</span></a>
                                </td>
                            </tr>
                            <tr id ="description-<?= $vo['id'] ?>" style="display: none">
                                <td></td>
                                <td colspan="6">
                                    <?= $vo['description'] ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--列表-->
<script type="text/html" id="listModel">
    {{each list as value i}}
    <tr id = "{{value.id}}">
        <td class="feed-element" style="width: 70px;">
            <img alt="image" class="img-rounded m-t-xs img-responsive" src="{{value.cover}}" width="64" height="64">
        </td>
        <td>
            <h4>{{value.title}}</h4>
            <small> 标识：{{value.name}}</small>
        </td>
        <td>{{value.version}}</td>
        <td>{{value.author}}</td>
        <td>{{value.brief_introduction}} <a href="#" class="show-description">详细介绍</a></td>
        <td>
            {{if value.is_mini_program == 1}}
            <span class="label label-info">Api/小程序</span>
            {{/if}}
            {{if value.is_setting == 1}}
            <span class="label label-info">全局设置</span>
            {{/if}}
            {{if value.is_rule == 1}}
            <span class="label label-info">嵌入规则</span>
            {{/if}}
            {{if value.is_hook == 1}}
            <span class="label label-info">钩子</span>
            {{/if}}
        </td>
        <td>
            <a href="{{value.upgradeConfigUrl}}" onclick="rfTwiceAffirm(this, '确认更新配置吗？', '会重载最新模块的配置');return false;"><span class="btn btn-primary btn-sm">更新配置</span></a>
            <a href="{{value.upgradeUrl}}" onclick="rfTwiceAffirm(this, '确认更新数据吗？', '会执行更新数据库字段升级等功能');return false;"><span class="btn btn-primary btn-sm">更新数据</span></a>
            <a href="{{value.ajaxEditUrl}}"><span class="btn btn-primary btn-sm">更新数据</span></a>
            {{if value.status == 0 }}
            <span class="btn btn-primary btn-sm" onclick="rfStatus(this)">启用</span>
            {{else}}
            <span class="btn btn-default btn-sm"  onclick="rfStatus(this)">禁用</span>'
            {{/if}}
            <a href="{{value.uninstallUrl}}" data-method="post"><span class="btn btn-warning btn-sm">卸载</span></a>
        </td>
    </tr>
    <tr id ="description-{{value.id}}" style="display: none">
        <td></td>
        <td colspan="6">
            {{value.description}}
        </td>
    </tr>
    {{/each}}
</script>

<script>
    $('.query').keyup(function () {
        var value = $(this).val();
        $('#listAddons').html('');
        $.ajax({
            type : "get",
            url : "<?=  Url::to(['uninstall'])?>",
            dataType : "json",
            data : {keyword:value},
            success : function(data){
                if(data.code == 200) {
                    $('#listAddons').html('');
                    var html = template('listModel', data.data);
                    $('#listAddons').append(html);
                }else{
                    alert(data.message);
                }
            }
        });
    });

    // 显示或者隐藏介绍
    $(document).on("click",".show-description",function(){
        var id = $(this).parent().parent().attr('id');

        if($("#description-"+id).is(":hidden")){
            $("#description-"+id).show();
        }else{
            $("#description-"+id).hide();
        }
    });
</script>
