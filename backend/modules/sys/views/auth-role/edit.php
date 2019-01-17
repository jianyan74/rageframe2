<?php
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? "创建" : "编辑";
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \yii\helpers\Html::cssFile('@web/resources/plugins/jsTree/style.min.css')?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">上级信息：<?= $parentTitle ?></h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'name', ['labelOptions' => ['label' => '角色名称']])->textInput(['id' => 'name']) ?>
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><div id="userTree"></div></div>
                <div class="col-sm-5"><div id="plugTree"></div></div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="button" onclick="submitForm()">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- jsTree plugin javascript -->
<?= \yii\helpers\Html::jsFile('@web/resources/plugins/jsTree/jstree.min.js')?>
<script>
    var userTreeId = "userTree";
    var userTreeCheckIds = JSON.parse('<?= json_encode($userTreeCheckIds) ?>');
    var userTreeData = JSON.parse('<?= json_encode($userTreeData) ?>');

    var plugTreeId = "plugTree";
    var plugTreeCheckIds = JSON.parse('<?= json_encode($plugTreeCheckIds) ?>');
    var plugTreeData = JSON.parse('<?= json_encode($plugTreeData) ?>');

    showCheckboxTree(userTreeData, userTreeId, userTreeCheckIds);
    showCheckboxTree(plugTreeData, plugTreeId, plugTreeCheckIds);

    /**
     * 带checkbox的树形控件使用说明
     * @data 应该是一个js数组
     * @id: 将树渲染到页面的某个div上，此div的id
     * @checkId:需要默认勾选的数节点id；1.checkId="all"，表示勾选所有节点 2.checkId=[1,2]表示勾选id为1,2的节点
     * 节点的id号由url传入json串中的id决定
     */
    function showCheckboxTree(data, id, checkId){

        for (var i = 0; i < data.length; i++){
            var dataVal = data[i]['id'];
            dataVal = dataVal.replace(/\//g, '---');
            data[i]['id'] = dataVal.replace(/:/g, '--');
        }

        for (var j = 0; j < checkId.length; j++){
            var checkVal = checkId[j];
            checkVal = checkVal.replace(/\//g, '---');
            checkId[j] = checkVal.replace(/:/g, '--');
        }

        menuTree = $("#"+id).bind("loaded.jstree", function(e,data){
            $("#"+id).jstree("open_all");
            $("#"+id).find("li").each(function(){
                if (checkId == 'all') {
                    $("#"+id).jstree("check_node", $(this));
                }

                if (checkId instanceof Array){
                    for (var i = 0; i < checkId.length; i++) {
                        if ($(this).attr("id") == checkId[i]){
                            $("#"+id).jstree("check_node", $(this));
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

        // 加载完毕关闭所有节点
        $("#"+id).bind('ready.jstree',function (obj, e) {
            $("#"+id).jstree('close_all');
        });
    }

    /**
     * 获取所有选择的数据
     *
     * @param treeId
     */
    function getCheckTreeIds(treeId) {
        // 打开所有的节点，不然获取不到子节点数据
        $("#"+treeId).jstree('open_all');

        var ids = [];
        var treeNode = $("#"+treeId).jstree(true).get_selected(true);

        for (var i = 0; i < treeNode.length; i++) {

            var node = treeNode[i];
            var nodeId = checkMatch(node.original.id);

            // 判断是否重复
            if($.inArray(nodeId, ids) == -1) {
                ids.push(nodeId);
            }

            for (var j = 0; j < node.parents.length; j++) {
                // 判断是否重复
                var parentId = checkMatch(node.parents[j]);
                if (parentId != "#" && $.inArray(parentId, ids) == -1) {
                    ids.push(parentId);
                }
            }
        }

        return ids;
    }

    // 转换字符串
    function checkMatch(checkId) {
        checkId = checkId.replace(/---/g, '/');
        checkId = checkId.replace(/--/g, ':');

        return checkId;
    }

    // 提交表单
    function submitForm(){

        var userTreeIds = getCheckTreeIds(userTreeId);
        var plugTreeIds = getCheckTreeIds(plugTreeId);

        $.ajax({
            type :"post",
            url : "<?= \yii\helpers\Url::to(['edit', 'name' => $name])?>",
            dataType : "json",
            data : {
                name : $("#name").val(),
                parent_key : "<?= $parentKey; ?>",
                userTreeIds : userTreeIds,
                plugTreeIds : plugTreeIds
            },
            success : function(data){
                if (data.code == 200) {
                    window.location = "<?= \yii\helpers\Url::to(['index'])?>";
                } else {
                    rfError(data.message);
                }
            }
        });
    }
</script>