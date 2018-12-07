<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;

$this->title = $allMediaType[$mediaType];
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<style>
    /*!*给大盒子添加样式*!*/
    .inlineBlockContainer{
        width:100%;
        /*下面代码是兼容各个浏览器的，并实现了四列，没两列之间间距为30px，*/
        /*火狐*/
        -moz-column-count:5;
        -moz-column-gap:0;
        -moz-column-rule:0 solid #ff0000;
        /*谷歌*/
        -webkit-column-count:5;
        -webkit-column-gap:0;
        -webkit-column-rule:0 solid #ff0000;
        /*Opera浏览器*/
        -o-column-count:5;
        -o-column-gap:0;
        -o-column-rule:0 solid #ff0000;
    }

    /*!*小盒子内容区的样式，display:inline-block：实现 效果*!*/
    .inlineBlockContainer .normalPaddingRight{
        width:100%;
        display:inline-block
    }
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <?= $this->render('_nav', [
        'allMediaType' => $allMediaType,
        'mediaType' => $mediaType,
        'keywords' => $keywords,
        'count' => $pages->totalCount
    ])?>
    <div class="row" style="margin-top: 25px">
        <div class="col-sm-12">
            <div class="inlineBlockContainer col5 vAlignTop separateChildren">
                <?php foreach ($models as $item){ ?>
                    <div class="normalPaddingRight">
                        <div class="borderColorGray separateChildrenWithLine whiteBG" style="margin-bottom: 30px;">
                            <?php foreach ($item['news'] as $index => $news){ ?>
                                <div class="normalPadding relativePosition postItem">
                                    <?php if($index == 0){ ?>
                                        <div style="background-image: url(<?= Url::to(['analysis/image','attach'=>$news['thumb_url']]) ?>); height: 160px" class="backgroundCover relativePosition mainPostCover">
                                            <div class="bottomBar"><?= $news['title'] ?></div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="flex-row">
                                            <div class="flex-col normalPadding"><?= $news['title'] ?></div>
                                            <div style="background-image: url(<?= Url::to(['analysis/image', 'attach' => $news['thumb_url']]) ?>);" class="backgroundCover subPostCover"></div>
                                        </div>
                                    <?php } ?>
                                    <div class="halfOpacityBlackBG absoluteFullSize" style="display: none;">
                                        <?php if($item['link_type'] == 1){ ?>
                                            <?php if($index == 0){ ?>
                                                <a class="fontColorWhite" href="<?= $news['media_url'] ?>" target="_blank" style="left:25%;top: 50%;position: absolute;">文章预览</a>
                                                <a class="fontColorWhite" href="<?= Url::to(['preview','attach_id' => $item['id'], 'mediaType' => $mediaType])?>" data-toggle='modal' data-target='#ajaxModal' style="right:25%;top: 50%;position: absolute;">手机预览</a>
                                            <?php }else{ ?>
                                                <a class="absoluteCenter fontColorWhite" href="<?= $news['media_url'] ?>" target="_blank">文章预览</a>
                                            <?php } ?>
                                        <?php }else{ ?>
                                            <a class="absoluteCenter fontColorWhite" href="<?= $news['media_url'] ?>" target="_blank">本地预览 <i class="fa fa-question-circle" title="本地文章,不可以群发"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="flex-row hAlignCenter normalPadding postToolbar">
                                <?php if($item['link_type'] == 1){ ?>
                                    <div class="flex-col"><a href="<?= Url::to(['send','attach_id'=> $item['id'], 'mediaType' => $mediaType])?>"  title="群发" data-toggle='modal' data-target='#ajaxModal'><i class="fa fa-send"></i></a></div>
                                    <div class="flex-col"><a href="<?= Url::to(['news-edit','attach_id'=> $item['id'], 'link_type' => $item['link_type']])?>" title="编辑"><i class="fa fa-pencil"></i></a></div>
                                <?php }else{ ?>
                                    <div class="flex-col"><a href="<?= Url::to(['news-edit','attach_id'=> $item['id'], 'link_type' => $item['link_type']])?>" title="编辑"><i class="fa fa-edit"></i></a></div>
                                <?php } ?>
                                <div class="flex-col"><a href="<?= Url::to(['delete','attach_id'=> $item['id'], 'mediaType' => $mediaType])?>" onclick="rfDelete(this, '删除后会删除素材对应的自动回复和等待群发');return false;" title="删除"><i class="fa fa-trash"></i></a></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        // 显示/隐藏“预览文章”按钮
        $('.postItem').mouseenter(function(e){
            $(e.currentTarget).find('.halfOpacityBlackBG').show();
        });
        $('.postItem').mouseleave(function(e){
            $(e.currentTarget).find('.halfOpacityBlackBG').hide();
        });

        // 弹出框选择新建图文类型
        var postType1Link = "<?= Url::to(['news-edit','model' => 'perm', 'link_type' => 1])?>";
        var postType2Link = "<?= Url::to(['news-edit','model' => 'perm', 'link_type' => 2])?>";
        $('#createPostBtn').click(function(){
            layer.open({
                type: 1,
                title: '新建图文消息',
                area: ['500px', '340px'],
                shadeClose: true,
                content: '<div class="farPadding separateChildren further">' +
                '<a class="farPadding borderColorGray displayAsBlock" href="' + postType1Link + '">' +
                '<div class="fontSizeL">创建微信图文</div>' +
                '<div class="fontColorGray">微信图文消息会自动同步至微信素材库，并可以直接群发给粉丝</div>' +
                '</a>' +
                '<a class="farPadding borderColorGray displayAsBlock" href="' + postType2Link + '">' +
                '<div class="fontSizeL">创建图文连接</div>' +
                '<div class="fontColorGray">点击图文直接跳转至指定链接，可用于自动回复及认证号菜单配置，不能同步至微信素材库。</div>' +
                '</a>' +
                '</div>'
            });
        });
        //
    })
</script>
