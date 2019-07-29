<?php
use common\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\helpers\Html;
use common\models\wechat\Menu;
use common\models\wechat\MenuProvinces;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => Menu::$typeExplain[$type], 'url' => ['index', 'type' => $type]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::jsFile('@web/resources/dist/js/vue.min.js')?>
<?= Html::jsFile('@web/resources/dist/js/sortable.min.js')?>
<?= Html::jsFile('@web/resources/dist/js/vuedraggable.min.js')?>

<div id="vueArea" class="wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <!-- 菜单编辑模式 -->
        <div class="col-sm-3" style="width: 352px">
            <div class="ibox float-e-margins" style="box-shadow: 3px 3px 3px #ccc;">
                <div class="phone-header">
                    <span class="ng-binding">自定义菜单</span>
                </div>
                <div class="flex-row flex-vDirection menuView">
                    <div class="flex-col"></div>
                    <div>
                        <draggable v-model="list" :options="{group:'mainMenu'}" class="flex-row phone-foot">
                            <div v-for="(item,index) in list" class="flex-col custommenu">
                                <div class="custommenu_sub_container">
                                    <draggable v-model="item.sub_button" :options="{group:'subMenu' + index}">
                                        <div v-for="sub in item.sub_button">
                                            <a class="btn btn-block btn-white" :class="{active:crtItem === sub}" @click="crtItem = sub">{{sub.name}}</a>
                                        </div>
                                    </draggable>
                                    <div v-show="item.sub_button.length < maxSubItemCount"><a class="btn btn-block btn-white" @click="addSubItem(item.sub_button)"><i class="fa fa-plus"></i></a></div>
                                </div>
                                <a class="btn btn-block btn-white" :class="{active:crtItem === item}" @click="crtItem = item">{{item.name}}</a>
                            </div>
                            <div class="flex-col" v-show="list.length < maxItemCount"><a class="btn btn-block btn-white" @click="addItem"><i class="fa fa-plus"></i> 添加菜单</a></div>
                        </draggable>
                    </div>
                </div>
            </div>
            <div class="form-group farPaddingJustV">
                <div class="hAlignCenter">
                    <?php if ($type == 1 || (empty($model['id']) && $type == 2)){ ?>
                        <a class="btn btn-primary" @click="submitForm">保存</a>
                    <?php } ?>
                    <a class="btn btn-white" @click="back">返回</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">菜单标题</h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'title')->textInput()->hint('给菜单起个名字吧！以便查找')->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($type == Menu::TYPE_INDIVIDUATION){ ?>
            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">显示对象</h3>
                    </div>
                    <div class="box-content clearfix normalPaddingJustV">
                        <div class="col-sm-12">
                            <div class="col-sm-3">
                                <?= $form->field($model, 'sex')->dropDownList(Yii::$app->params['individuationMenuSex'],[
                                    'disabled' => $model->isNewRecord ? false : true
                                ])->label('性别') ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($model, 'client_platform_type')->dropDownList(Yii::$app->params['individuationMenuClientPlatformType'],[
                                    'disabled' => $model->isNewRecord ? false : true
                                ])->label('系统') ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($model, 'language')->dropDownList(Yii::$app->params['individuationMenuLanguage'], [
                                    'disabled' => $model->isNewRecord ? false : true
                                ])->label('语言') ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($model, 'tag_id')->dropDownList(ArrayHelper::map($fansTags,'id','name'),[
                                    'prompt' => '不限',
                                    'disabled' => $model->isNewRecord ? false : true
                                ])->label('标签') ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'province')->dropDownList(Yii::$app->services->wechatMenuProvinces->getMapList(1),[
                                    'prompt' => '不限',
                                    "@change" => "province",
                                    'disabled' => $model->isNewRecord ? false : true
                                ])->label('省/直辖市') ?>
                            </div>
                            <div class="col-sm-6">
                                <?php if ($model->isNewRecord){?>
                                    <?= $form->field($model, 'city')->dropDownList([],['prompt' => '不限'])->label('市') ?>
                                <?php }else{ ?>
                                    <?= $form->field($model, 'city')->dropDownList([$model->city => $model->city],['disabled' => true])->label('省/直辖市') ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!--
        	added by wzq 自定义菜单操作区
        -->
        <div class="col-sm-6" v-if="crtItem">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">菜单设置</h3>
                    <a @click="deleteCrtItem" class="pull-right">删除菜单</a>
                </div>
                <div class="box-content clearfix normalPaddingJustV">
                    <div class="col-sm-12 p-lg" style="padding-top: 0">
                        <div class="form-group">
                            <label class="control-label">菜单名称</label>
                            <input class="form-control" name="CustomMenu[title]" v-model="crtItem.name" aria-required="true" type="text">
                            <div class="help-block"></div>
                        </div>
                        <div class="form-group" v-show="!hasSubItem(crtItem)">
                            <label class="control-label">菜单动作</label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php foreach ($menuTypes as $key => $menuType){ ?>
                                        <label>
                                            <input type="radio" value="<?= $key ?>" name="ipt" v-model="crtItem.type"> <i></i> <?= $menuType['name'] ?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" v-show="!hasSubItem(crtItem) && needContent(crtItem)">
                            <hr>
                            <input class="form-control" name="value" value="" aria-required="true" type="text" v-model="crtItem.content">
                        </div>
                        <!-- 小程序 -->
                        <div class="form-group" v-show="!hasSubItem(crtItem) && needMiniprogram(crtItem)">
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">APPID</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="value" placeholder="请确保小程序与公众号已关联，填写小程序的APPID" value="" aria-required="true" type="text" v-model="crtItem.appid">
                                    <span class="help-block"><a href="http://weixiao.qq.com/notice/view?mid=0&cid=2&id=274" target="_blank">如何获取?</a></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">页面</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="value" placeholder="请填写跳转页面的小程序访问路径" value="" aria-required="true" type="text" v-model="crtItem.pagepath">
                                    <span class="help-block"><a href="http://weixiao.qq.com/notice/view?mid=0&cid=2&id=275" target="_blank">填写指引</a></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">备用网页</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="value" placeholder="写入要跳转的链接" value="" aria-required="true" type="text" v-model="crtItem.url">
                                    <span class="help-block">旧版微信客户端不支持小程序，用户点击菜单时会打开该网页</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
    $(function(){
        // 兼容老IE
        document.body.ondrop = function (event) {
            event = event || window.event;
            if (event.preventDefault) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                event.returnValue = false;
                event.cancelBubble = true;
            }
        };

        var list = '<?= json_encode($model->menu_data ?? []) ?>';
        list = JSON.parse(list);

        for (var i=0; i<list.length; i++){
            var item = list[i];
            if(!item.sub_button) item.sub_button = [];

            if(item.type == 'click')
            {
                item.content = item.key
            }

            if(item.type == 'view')
            {
                item.content = item.url
            }

            // 子菜单判断
            for (var j = 0; j < item.sub_button.length; j++){
                var subButton = item.sub_button[j];
                if(subButton.type == 'click')
                {
                    subButton.content = subButton.key
                }

                if(subButton.type == 'view')
                {
                    subButton.content = subButton.url
                }
            }
        }

        // console.log(list);
        var vueArea = new Vue({
            data:{
                list: list ? list : [],
                maxItemCount: 3,
                maxSubItemCount: 5,
                crtItem: null,
                isSortMode: false
            },
            methods: {
                addItem: function(){
                    var newOne = {name: '菜单名称', sub_button:[], type:'click',content:''};
                    this.list.push(newOne);
                    this.crtItem = newOne;
                },
                addSubItem: function(subList){
                    var newOne = {name:'子菜单名称', type:'click',content:''};
                    subList.push(newOne);
                    this.crtItem = newOne;
                },
                deleteCrtItem: function(){
                    var self = this;

                    function doDelete(){
                        var itemIndex;
                        for (var i = 0; i < self.list.length; i++) {
                            var item = self.list[i];
                            if(item == self.crtItem)
                            {
                                self.list.splice(i, 1);
                                self.crtItem = null;
                                return;
                            }
                            itemIndex = item.sub_button.indexOf(self.crtItem);
                            if (itemIndex >= 0) {
                                item.sub_button.splice(itemIndex, 1);
                                self.crtItem = null;
                                return;
                            }
                        }
                    }

                    if(self.crtItem.sub_button){
                        appConfirm("您确定要删除这个菜单吗？", "删除后将无法恢复，请谨慎操作！", doDelete)
                    }else{
                        doDelete();
                    }
                },
                submitForm: function(){
                    // 检查子菜单类别是否都填了
                    var self = this;
                    function checkValidate(item){
                        var needContent = self.needContent(item);
                        if(needContent && !item.content)
                        {
                            rfAffirm('请填写"'+item.name+'"的' + needContent);
                            self.crtItem = item;
                            return false;
                        }
                        if(item.type == 'view' && !new RegExp('^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&%\$#\=~_\-]+))*$').test(item.content))
                        {
                            rfAffirm('您填写的链接地址格式不正确');
                            self.crtItem = item;
                            return false;
                        }
                        // 小程序判断
                        if(item.type == 'miniprogram')
                        {
                            if(!item.appid)
                            {
                                rfAffirm('请填写appid');
                                self.crtItem = item;
                                return false;
                            }
                            if(!item.pagepath)
                            {
                                rfAffirm('请填写页面');
                                self.crtItem = item;
                                return false;
                            }
                            if(!item.url)
                            {
                                rfAffirm('请填写备用网页');
                                self.crtItem = item;
                                return false;
                            }
                        }

                        return true;
                    }
                    for(var i = 0; i < this.list.length; i++) {
                        var item = this.list[i];
                        if(this.hasSubItem(item))
                        {
                            for(var j = 0; j < item.sub_button.length; j++)
                            {
                                var subItem = item.sub_button[j];
                                if(!checkValidate(subItem))
                                {
                                    return;
                                }
                            }
                        }
                        else
                        {
                            if(!checkValidate(item))
                            {
                                return;
                            }
                        }
                    }

                    var prevent = true;
                    if(prevent){
                        prevent = false;
                        var id = '<?= $model->id ?>';
                        var type = '<?= $type ?>';
                        var title = $('#menu-title').val();
                        var sex = $('#menu-sex').val();
                        var language = $('#menu-language').val();
                        var province = $('#menu-province').val();
                        var city = $('#menu-city').val();
                        var client_platform_type = $('#menu-client_platform_type').val();
                        var tag_id = $('#menu-tag_id').val();

                        $.ajax({
                            type:"post",
                            url:"<?= Url::to(['edit'])?>",
                            dataType: "json",
                            data: {id:id,
                                list:this.list,
                                type:type,
                                title:title,
                                city:city,
                                client_platform_type:client_platform_type,
                                province:province,
                                tag_id:tag_id,
                                sex:sex,
                                language:language},
                            success: function(data){
                                prevent = true;
                                if(data.code == 200) {
                                    window.location = '<?= Url::to(['index','type' => $type]) ?>';
                                }else{
                                    rfAffirm(data.message);
                                }
                            }
                        });
                    }
                },
                back: function(){
                    window.history.go(-1);
                },
                province: function(){
                    var val = $('#menu-province').val();

                    $.ajax({
                        type:"GET",
                        url:"<?= Url::to(['menu-provinces/index'])?>",
                        dataType: "json",
                        data: {title:val},
                        success: function(data){
                            $("#menu-city").html(data.data);
                        }
                    });
                },
                hasSubItem: function(item){
                    return item.sub_button && item.sub_button.length > 0;
                },
                needContent: function(item){
                    var dic = {click: '触发关键字', view: '跳转链接'};
                    return dic[item.type];
                },
                needMiniprogram: function(item){
                    var dic = {miniprogram: '关联小程序'};
                    return dic[item.type];
                },
            }
        }).$mount('#vueArea');
    });
</script>