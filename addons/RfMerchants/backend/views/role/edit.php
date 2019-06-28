<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\helpers\Html;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '商户管理', 'url' => ['merchant/index']];
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index', 'merchant_id' => $merchant_id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?= Html::cssFile('@web/resources/plugins/jsTree/style.min.css')?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'pid')->dropDownList($dropDownList) ?>
                <?= $form->field($model, 'title')->textInput(); ?>
                <?= $form->field($model, 'sort')->textInput(); ?>
                <?php // = $form->field($model, 'status')->radioList(StatusEnum::$listExplain); ?>
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><div id="userTree"></div></div>
                <div class="col-sm-5"><div id="plugTree"></div></div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary" type="button" onclick="submitForm()">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- jsTree plugin javascript -->
<?= Html::jsFile('@web/resources/plugins/jsTree/jstree.min.js')?>
<script>
    var userTreeId = "userTree";
    var userTreeCheckIds = JSON.parse('<?= json_encode($defaultCheckIds) ?>');
    var userTreeData = JSON.parse('<?= json_encode($defaultFormAuth) ?>');

    var plugTreeId = "plugTree";
    var plugTreeCheckIds = JSON.parse('<?= json_encode($addonsCheckIds) ?>');
    var plugTreeData = JSON.parse('<?= json_encode($addonsFormAuth) ?>');

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
            var nodeId = node.original.id;

            // 判断是否重复
            if($.inArray(nodeId, ids) == -1) {
                ids.push(nodeId);
            }

            for (var j = 0; j < node.parents.length; j++) {
                // 判断是否重复
                var parentId = node.parents[j];
                if (parentId != "#" && $.inArray(parentId, ids) == -1) {
                    ids.push(parentId);
                }
            }
        }
        $("#"+treeId).jstree('close_all');
        return ids;
    }

    // 提交表单
    function submitForm(){

        var userTreeIds = getCheckTreeIds(userTreeId);
        var plugTreeIds = getCheckTreeIds(plugTreeId);

        rfAffirm('保存中...');

        $.ajax({
            type :"post",
            url : "<?= Url::to(['edit', 'id' => $model->id, 'merchant_id' => $merchant_id])?>",
            dataType : "json",
            data : {
                id : '<?= $model['id']?>',
                pid : $("#authrole-pid").val(),
                sort : $("#authrole-sort").val(),
                title : $("#authrole-title").val(),
                userTreeIds : userTreeIds,
                plugTreeIds : plugTreeIds
            },
            success : function(data){
                if (data.code == 200) {
                    window.location = "<?= Url::to(['index', 'merchant_id' => $merchant_id])?>";
                } else {
                    rfError(data.message);
                }
            }
        });
    }
</script>