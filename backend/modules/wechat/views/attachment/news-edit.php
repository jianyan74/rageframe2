<?php
use common\helpers\Url;
use yii\widgets\ActiveForm;
use common\helpers\Html;

$this->title = $attachment->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '图文素材', 'url' => ['index', 'type' => 'news']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::jsFile('@web/resources/dist/js/vue.min.js')?>

<style>
    .leftArea{
        width: 180px; box-sizing: content-box;
    }
    .postList > *{
        height: 140px;
    }
    .addPostBtn{
        background-color: transparent;
        text-align: center;
        line-height: 140px;
    }
    .addPostBtn:before{
        content: '+';
        font-size: 45px;
        font-weight: bold;
    }
    .uploaderPlaceHolder{
        display: inline-block;
        width: 160px; height: 160px;
        line-height: 160px; text-align: center;
        background-color: #eee;
    }
    .postItem{
        border: 4px inset transparent;
    }
    .postItem.active{
        border: 1px solid #62a8ea !important;
    }
    .subPostCover{
        position: absolute; right: 10px; bottom: 10px;
    }
</style>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-12" id="vueArea">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">回复内容</h3>
            </div>
            <div class="box-content overflowHidden">
                <div class="row">
                    <div class="col-sm-12 noHorizontalPadding">
                        <div class="flex-row">
                            <div class="leftArea farPaddingJustH">
                                <div class="separateFromNextBlock">图文列表</div>
                                <div class="postList borderColorGray separateChildrenWithLine">
                                    <!-- 文章列表 -->
                                    <div class="normalPadding postItem" v-for="(item, index) in postList" :class="{active:crtPost === item}" @click="crtPost = item">
                                        <!-- 头条文章 -->
                                        <div v-if="index == 0" class="mainPostCover fullHeight relativePosition backgroundCover" :style="{backgroundImage:'url(' + item.thumb_url + ')'}">
                                            <div class="bottomBar">{{item.title}}</div>
                                        </div>
                                        <!-- 次条文章 -->
                                        <div v-else class="relativePosition fullHeight">
                                            <div>{{item.title}}</div>
                                            <div class="subPostCover backgroundCover" :style="{backgroundImage:'url(' + item.thumb_url + ')'}">
                                            </div>
                                            <div class="bottomBar flex-row flex-hAlignBalance" v-show="item === crtPost">
                                                <div class="separateInlineChildren">
                                                    <a @click="moveForward(index)"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                                    <a v-show="index < postList.length - 1" @click="moveBackward(index)"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                                </div>
                                                <div v-show="!isEditMode"><a @click="removePost(index)"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="addPostBtn" v-show="postList.length < postMaximum && !isEditMode" @click="addPost"></div>
                                </div>
                            </div>
                            <div class="flex-col borderColorGray">
                                <div class="flex-row">
                                    <div class="borderRightColorGray flex-col">
                                        <div><input class="appInput largeSize fullWidth borderBottomColorGray" placeholder="请输入标题" v-model="crtPost.title"></div>
                                        <div><input class="appInput fullWidth borderBottomColorGray" placeholder="请输入作者" v-model="crtPost.author"></div>
                                        <div><input class="appInput fullWidth" placeholder="请输入连接地址" v-model="crtPost.content_source_url"></div>
                                        <?php if ($attachment['link_type'] == 1){ ?>
                                            <?= \common\widgets\ueditor\UEditor::widget([
                                                'id' => 'content',
                                                'attribute' => 'content',
                                                'name' => 'content',
                                                'value' => '',
                                                'formData' => [
                                                    'drive' => 'local',
                                                    'showDrive' => 'WechatAttachment',
                                                ],
                                                'config' => [
                                                    'toolbars' => [
                                                        [
                                                            'fullscreen', 'source', 'undo', 'redo', '|',
                                                            'customstyle', 'paragraph', 'fontfamily', 'fontsize'
                                                        ],
                                                        [
                                                            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat',
                                                            'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                                                            'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', '|',
                                                            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                                                            'directionalityltr', 'directionalityrtl', 'indent', '|'
                                                        ],
                                                        [
                                                            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                                                            'link', 'unlink', '|','simpleupload',
                                                            'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'insertcode', 'pagebreak', '|',
                                                            'horizontal', 'inserttable', '|',
                                                            'print', 'preview', 'searchreplace', 'help'
                                                        ]
                                                    ],
                                                ]
                                            ]) ?>
                                        <?php } ?>
                                    </div>
                                    <div style="width:200px;">
                                        <div class="borderBottomColorGray farPadding">
                                            <div class="separateFromNextBlockFar">发布样式编辑</div>
                                            <div class="separateFromNextBlock">
                                                <div>封面<span class="fontColorGray">(小图片建议尺寸：200像素x200像素)</span></div>
                                                <div @click="uploadNewthumb_url" class="cursorPointer">
                                                    <div class="uploaderPlaceHolder borderColorGray fontSizeL" v-if="!crtPost.thumb_url">点击上传图片</div>
                                                    <img :src="crtPost.thumb_url" style="max-width:100%;" v-else/>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="checkbox" id="showCoverInTop" v-model="crtPost.show_cover_pic"/>
                                                <label for="showCoverInTop">在正文顶部显示封面图</label>
                                            </div>
                                        </div>
                                        <div class="borderBottomColorGray farPadding">
                                            <div class="separateFromNextBlock">摘要<span class="fontColorGray">(选填，如果不填写会默认抓取正文前54个字)</span></div>
                                            <textarea class="appTextarea fullWidth" rows="4" v-model="crtPost.digest"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group normalPadding">
                            <div class="hAlignCenter">
                                <a class="btn btn-primary" @click="submitForm">保存</a>
                                <a class="btn btn-white" onclick="history.go(-1)">返回</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 上传组件不需要显示出来，我只需要使用它的功能即可 -->
    <div hidden>
        <?= \common\widgets\webuploader\Files::widget([
            'name'  =>"thumb_url",
            'value' =>  '',
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],
                'formData' => [
                    'drive' => 'local',
                ],
                'callback' => 'setUploadedImg',
                'independentUrl' => true,
            ],
        ])?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    function DataPost(){
        this.title = '';
        this.thumb_url = '';
        this.author = '';
        this.content = '';
        this.digest = '';
        this.content_source_url = '';
        this.show_cover_pic = false;
    }

    $(function(){
        var link_type = "<?= $attachment['link_type'] ?>";
        var ue, thumb_urlImagePreview = $('.uploadedImg'), ueReadyHandlers = [];
        function init(){

            if (link_type == 1){
                UE.getEditor('content').ready(function(){
                    ue = this;
                    for(var i=0; i<ueReadyHandlers.length; i++)
                    {
                        ueReadyHandlers[i]();
                    }
                    ueReadyHandlers.length = 0;
                });
            }

            var vueArea = new Vue({
                el: '#vueArea',
                data:{
                    postMaximum: 8,
                    postList: [],
                    crtPost: new DataPost(),
                    isEditMode: false
                },
                methods: {
                    addPost: function(){
                        var d = new DataPost();
                        this.postList.push(d);
                        this.crtPost = d;
                    },
                    removePost: function(index){
                        if(this.crtPost === this.postList[index])
                        {
                            this.crtPost = this.postList[index - 1];
                        }
                        this.postList.splice(index, 1);
                    },
                    moveForward: function(index){
                        var preIndex = index - 1;
                        var newArray = this.postList.concat();
                        newArray[preIndex] = newArray.splice(index, 1, newArray[preIndex])[0];
                        this.postList = newArray;
                        this.crtPost = this.postList[preIndex];
                    },
                    moveBackward: function(index){
                        var nextIndex = index + 1;
                        var newArray = this.postList.concat();
                        newArray[nextIndex] = newArray.splice(index, 1, newArray[nextIndex])[0];
                        this.postList = newArray;
                        this.crtPost = this.postList[nextIndex];
                    },
                    submitForm: function(){
                        if (link_type == 1){
                            this.crtPost.content = ue.getContent();
                        }

                        for(var i=0; i<this.postList.length; i++) {
                            var p = this.postList[i];
                            if(link_type == 1 && !this.validateFileds([p.title, p.thumb_url, p.author, p.content], ["图文标题不能留空", "请设置图文封面", "请填写图文作者","请填写图文内容"])) {
                                return;
                            }

                            if(link_type == 2 && !this.validateFileds([p.title, p.thumb_url], ["图文标题不能留空", "请设置图文封面"])) {
                                return;
                            }
                        }

                        rfAffirm('同步到微信中,请不要关闭当前页面');

                        var cloneAry = this.postList.concat();
                        for (var i=0; i<cloneAry.length; i++) {
                            cloneAry[i].show_cover_pic = cloneAry[i].show_cover_pic ? 1 : 0;
                        }

                        // ajax提交
                        $.ajax({
                            type:"post",
                            url:"<?= Url::to(['news-edit', 'attach_id' => $attachment['id'], 'link_type' => $attachment['link_type']])?>",
                            dataType: "json",
                            data: {
                                list:JSON.stringify(cloneAry) // 图文列表数据
                            },
                            success: function(data){
                                if(data.code == 200){
                                    window.location.href = "<?= Url::to(['index'])?>";
                                }else{
                                    rfError(data.message)
                                }
                            }
                        });
                    },
                    uploadNewthumb_url: function(){
                        $('.webuploader-container input').trigger('click');// 触发上传组件的选图功能
                    },
                    validateFileds: function(valueList, errorMsgList){
                        for(var i=0; i<valueList.length; i++) {
                            if(!valueList[i]) {
                                alert(errorMsgList[i]);
                                return false;
                            }
                        }
                        return true;
                    }
                },
                mounted: function(){
                    var self = this;
                    var list = <?= $list ?>;

                    // 上传组件上传完图片后会抛送此事件，此时将图片在服务器上的地址给到我们的crtPost.thumb_url里面
                    $(document).on('setUploadedImg', function(e, data, config){
                        if(config.name == 'thumb_url') {
                            self.crtPost.thumb_url = data.url;
                        }
                    });

                    if(list && list.length > 0) {
                        self.postList = list;

                        for (i=0;i< self.postList.length;i++){
                            var elem = self.postList[i];
                            elem.show_cover_pic = parseInt(elem.show_cover_pic) > 0
                        }

                        self.isEditMode = true;
                    } else {  // 新建回复规则的情况
                        self.addPost();
                        self.isEditMode = false;
                    }

                    self.crtPost = self.postList[0];
                },
                watch: {
                    crtPost: function(v, old){
                        function visitUE(){
                            old.content = ue.getContent();
                            ue.setContent(v.content);
                        }
                        ue ? visitUE() : ueReadyHandlers.push(visitUE);
                    }
                }
            });
        };

        setTimeout(init, 0);// 延迟一帧执行，为了让后面的UEditor.php中的那句UE.getEditor先执行，这样我们才能在init中通过UE.getEditor语句拿到已经初始化好的editor实例
    });
</script>