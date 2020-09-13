<style>
    .jstree-default .jstree-search {
        font-style: normal;
        color: rgb(55, 71, 79);
        font-weight: 700;
    }

    .jstree-default .jstree-clicked {
        background: #ffffff;
        border-radius: 2px;
        color: rgb(55, 71, 79);
        box-shadow: inset 0 0 0 #999;
    }

    .jstree-default .jstree-hovered {
        background: #ffffff;
        border-radius: 2px;
        color: rgb(55, 71, 79);
        box-shadow: inset 0 0 0 #999;
    }

    .jstree-default .node-icon {
        padding-left: 8px;
    }
</style>

<div class="row">
    <div class="col-xs-3">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $title; ?></h3>
                <div class="box-tools">
                    <button class="btn btn-primary btn-xs" onclick="nodeCreate()"><i class="icon ion-plus"></i> 创建
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <!-- 描述：搜索框 -->
                <div class="input-group m-b">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="请输入关键字..." id="search_ay"
                           aria-describedby="basic-addon1">
                </div>
                <div id="<?= $name; ?>"></div>
                <div class="m-t">
                    <span class="orange">按住拖动可更换上级</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">基本信息 <span class="jstree-table-id"></span></h3>
            </div>
            <div class="box-body">
                <div class="col-lg-12" id="container">
                </div>
            </div>
            <div class="box-footer text-center">
                <span class="btn btn-primary" onclick="nodeBeforeSubmit()">保存</span>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    var treeId = $name;
    var editUrl = '$editUrl';
    var deleteUrl = '$deleteUrl';
    var treeCheckIds = JSON.parse('[]');
    var treeData = $defaultData;
    
    showCheckboxTree(treeData, $(treeId).attr('id'), treeCheckIds);
    
    /**
     * 带checkbox的树形控件使用说明
     * @data 应该是一个js数组
     * @id: 将树渲染到页面的某个div上，此div的id
     * @checkId:需要默认勾选的数节点id；1.checkId="all"，表示勾选所有节点 2.checkId=[1,2]表示勾选id为1,2的节点
     * 节点的id号由url传入json串中的id决定
     */
    function showCheckboxTree(data, id, checkId) {
        var that = $("#" + id);
    
        menuTree = that.bind("loaded.jstree", function (e, data) {
            // that.jstree("open_all");
            that.find("li").each(function () {
                if (checkId == 'all') {
                    that.jstree("check_node", $(this));
                }
            });
        }).jstree({
            "core": {
                "check_callback" : true,
                "data": data,
                "attr": {
                    "class": "jstree-checked"
                }
            },
             "check_callback" : function(operation, node, node_parent, node_position, more){
                // if(operation === "move_node"){
                //     var node = this.get_node(node_parent);
                //     if(node.id === "#"){
                //         alert("根结点不可以删除");
                //         return false;
                //     }
                //     if(node.state.disabled){
                //         alert("禁用的不可以删除");
                //         return false;
                //     }
                // }else if(operation === "delete_node"){
                //     var node = this.get_node(node_parent);
                //     if(node.id === "#"){
                //         alert("根结点不可以删除");
                //         return false;
                //     }
                // }
                // return true;
            },
            "types": {
                "default": {
                    "valid_children": ["default", "file"]
                },
                "file": {
                    "icon": "glyphicon glyphicon-file",
                    "valid_children": []
                }
            },
            "plugins": [
                "contextmenu", "dnd", "search",
                "types","sort","state",
            ],
            "contextmenu": {
                "items": {
                    "create": null,
                    "rename": null,
                    "remove": null,
                    "ccp": null
                }
            },
            'sort' : function(a, b) {
               // console.log(a, b);
            // you can do custom sorting here
            // based on your user action you can return 1 or -1
            // 1 show on top -1 for show bottom 
            }
        });
    
        // 加载完毕关闭所有节点
        that.bind('ready.jstree', function (obj, e) {
            that.jstree('close_all');
        });
        // 监听打开事件
        that.on("open_node.jstree", function(e,data){ 
            var currentNode = data.node;  
            data.instance.set_icon(currentNode, "glyphicon glyphicon-folder-open"); 
        });
        // 监听关闭事件 
        that.on("close_node.jstree", function(e,data){ 
            var currentNode = data.node;  
            data.instance.set_icon(currentNode, "glyphicon glyphicon-folder-close"); 
        });
        // 鼠标移出事件
        that.on('dehover_node.jstree', function (e,data) { 
            $('.node-icon').remove();
        });
        // 鼠标移上事件
        that.on('hover_node.jstree', function (e,data) { 
            var node = data.node.original;
            $('#' + node.id + '_anchor').append("<i class='fa fa-plus node-icon node-create' onclick='nodeCreateChild(" + node.id + ")'></i><i class='fa fa-pencil-square-o node-icon node-create' onclick='nodeEdit(" + node.id + ")'></i><i class='fa fa-trash node-icon node-delete' onclick='nodeDelete(" + node.id + ")'></i>")
    
            console.log(data.node);
        });
        
        // 拖拽移动
        that.on('move_node.jstree', function(e,data) {    
         moveParentId = data.parent;
         if (moveParentId === '#'){
             moveParentId = 0;
           }
         
            $.ajax({
                type: "get",
                url: '$moveUrl',
                dataType: "json",
                data: {id: data.node.id, pid: moveParentId},
                success: function(html){
    
                }
            });
         });
    
        // 单击
        that.on("activate_node.jstree", function(e, data){
    
        });
        
          // 创建
        that.on("create_node.jstree", function(e, data){
           // alert("创建node节点");
        });
    
        // 修改
        that.on("rename_node.jstree", function(e, data){
          // alert("修改node节点");
        });
    
        // 删除
        that.on("delete_node.jstree", function(e, data){
           // alert("删除node节点");
        });
        
            // 查询节点名称
        var to = false;
        $("#search_ay").keyup(function(){
            if(to){
                clearTimeout(to);
            }
            to = setTimeout(function(){
                // 开启插件查询后 使用这个方法可模糊查询节点 
                that.jstree(true).search($('#search_ay').val());  
            },250);
        });
    
        $('.btn-tab').click(function(){ //选项事件   
            //alert($(this).attr("var"))  
            that.jstree(true).destroy();   //可做联级  
            that = jstree_fun($(this).attr("var"));//可做联级  
            //alert($(this).attr("var"))              
        });  
    
        $('.refresh').click(function(){ //刷新事件  
            that.jstree(true).refresh();
            that.jstree('uncheck_all');
        });
    }

JS
);
?>

<script>
    var isNodeNewRecord = false;
    var nodePid = 0;

    // 初始化
    $(document).ready(function () {
        nodeCreate();
    });

    function nodeCreate() {
        $('.jstree-table-id').text('');
        isNodeNewRecord = true;
        initEdit();
    }

    function nodeCreateChild(id) {
        isNodeNewRecord = true;
        initEdit('', id);
    }

    function initEdit(id = '', pid = 0) {
        layer.load(2);

        $.ajax({
            type: "get",
            url: "<?= $editUrl ?>",
            dataType: "html",
            data: {id: id, pid: pid},
            success: function (html) {
                $("#container").html(html);

                layer.closeAll('loading');
            }
        });
    }

    function nodeEdit(id) {
        layer.load(2);

        isNodeNewRecord = false;
        $('.jstree-table-id').text('- ID: ' + id);

        $.ajax({
            type: "get",
            url: "<?= $editUrl ?>",
            dataType: "html",
            data: {id: id},
            success: function (html) {
                $("#container").html(html);

                layer.closeAll('loading');
            }
        });
    }

    function nodeDelete(id) {
        var ref = $("#userTree").jstree(true);

        swal("确定要删除么吗?", {
            buttons: {
                cancel: "取消",
                defeat: '确定',
            },
            title: '确定要删除么吗?',
            text: '请谨慎操作',
        }).then((value) => {
            switch (value) {
                case "defeat":
                    $.ajax({
                        type: "get",
                        url: "<?= $deleteUrl ?>",
                        dataType: "json",
                        data: {id: id},
                        success: function (data) {
                            if (parseInt(data.code) === 200) {
                                ref.delete_node(id);
                                initEdit();
                                $('.jstree-table-id').text('');
                                rfMsg(data.message);
                            } else {
                                rfMsg(data.message);
                            }
                        }
                    });
                    break;
                default:
            }
        });
    }

    function nodeBeforeSubmit() {
        var data = $("#w0").serializeArray();
        var ref = $("#userTree").jstree(true);
        var sel = ref.get_selected();
        var id = '';

        // 创建
        if (isNodeNewRecord === false && sel.length > 0) {
            id = sel[0];
        }

        $.ajax({
            type: "post",
            url: "<?= $editUrl ?>?id=" + id,
            dataType: "json",
            data: data,
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    var res = data.data;

                    var createPid = parseInt(res.pid);
                    if (createPid === 0) {
                        createPid = '#';
                    }

                    if (isNodeNewRecord === false) {
                        ref.set_text(res.id, res.title);
                    } else {
                        // 创建新节点
                        ref.create_node(createPid, {
                            "id": res.id, // 节点ID
                            "text": res.title // 节点文本
                        });

                        $('.jstree-table-id').text('- ID: ' + res.id);
                    }

                    swal("操作成功", "小手一抖就打开了一个框", "success");
                } else {
                    rfMsg(data.message);
                }
            }
        });
    }
</script>
