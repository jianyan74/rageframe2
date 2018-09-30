<?php
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? "创建" : "编辑";
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="/backend/resources/css/plugins/jsTree/style.min.css" rel="stylesheet">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本信息</h5>
                </div>
                <div class="ibox-content">
                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                        ]
                    ]); ?>
                    <div class="col-md-12">
                        <?= $form->field($model, 'name',['labelOptions' => ['label' => '角色名称']])->textInput(['id' => 'name']) ?>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10"><div id="configTree"></div></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <div class="hr-line-dashed"></div>
                            <button class="btn btn-primary" type="button" onclick="getCheckboxTreeSelNode('configTree')">保存</button>
                            <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- jsTree plugin javascript -->
    <script src="/backend/resources/js/plugins/jsTree/jstree.min.js"></script>
    <script>
        var treeid = "configTree";
        var checkId = JSON.parse('<?= json_encode($checkId) ?>');
        var data = JSON.parse('<?= json_encode($formAuth) ?>');

        showCheckboxTree(data,treeid,checkId);
        /**
        * 带checkbox的树形控件使用说明
        * @data 应该是一个js数组
        * @id: 将树渲染到页面的某个div上，此div的id
        * @checkId:需要默认勾选的数节点id；1.checkId="all"，表示勾选所有节点 2.checkId=[1,2]表示勾选id为1,2的节点
        * 节点的id号由url传入json串中的id决定
        */
        function showCheckboxTree(data,id,checkId){
            treeid = id;
            menuTree = $("#"+id).bind("loaded.jstree",function(e,data){
                $("#"+id).jstree("open_all");
                $("#"+id).find("li").each(function(){
                    if (checkId == 'all') {
                        $("#"+id).jstree("check_node",$(this));
                    } if (checkId instanceof Array){
                        for (var i=0;i<checkId.length;i++){
                            if ($(this).attr("id") == checkId[i]){
                                $("#"+id).jstree("check_node",$(this));
                            }
                        }
                    }
                });
            }).jstree({
                "core" : {
                    "data": data,
                    "attr":{
                        "class":"jstree-checked"
                    }
                },
                "types" : {
                    "default" : {
                        "valid_children" : ["default","file"]
                    },
                    "file" : {
                        "icon" : "glyphicon glyphicon-file",
                        "valid_children" : []
                    }
                },
                "checkbox" : {
                    "keep_selected_style" : false,
                    "real_checkboxes" : true
                },
                "plugins" : [
                    "contextmenu", "dnd", "search",
                    "types","checkbox"
                ],
                "contextmenu":{
                    "items":{
                        "create":null,
                        "rename":null,
                        "remove":null,
                        "ccp":null
                    }
                }
            });
        }

        // 获取选中的id
        function getCheckboxTreeSelNode(treeid){
            // 打开所有的节点，不然获取不到子节点数据
            $("#"+treeid).jstree('open_all');

            var ids = [];
            $("#"+treeid).find("li").each(function(){
                var liid = $(this).attr("id");
                if ($("#" + liid + ">a").hasClass("jstree-clicked") || $("#" + liid + ">a>i").hasClass("jstree-undetermined")) {
                    ids.push(liid);
                }
            });

            $.ajax({
                type:"post",
                url:"",
                dataType: "json",
                data: {
                    originalName: "<?= $name; ?>",
                    name: $("#name").val(),
                    ids : ids
                },
                success: function(data){
                    if (data.code == 200) {
                        window.location = "<?= \yii\helpers\Url::to(['index'])?>";
                    } else {
                        rfError(data.message);
                    }
                }
            });

            return ids;
        }
    </script>
</div>