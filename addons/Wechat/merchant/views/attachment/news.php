<?php
use common\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\Html;

$this->title = $allMediaType[$mediaType];
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<?= $this->render('_nav', [
    'allMediaType' => $allMediaType,
    'mediaType' => $mediaType,
    'keywords' => $keywords,
    'count' => $pages->totalCount
]); ?>

<div class="row" style="margin-top: 25px">
    <div class="col-sm-12">
        <div class="box" style="background: #ecf0f5">
            <div class="inlineBlockContainer col5 vAlignTop separateChildren">
                <?php foreach ($models as $item){ ?>
                    <div class="normalPaddingRight" style="position:absolute">
                        <div class="borderColorGray separateChildrenWithLine whiteBG m-b-sm">
                            <?php foreach ($item['news'] as $index => $news){ ?>
                                <div class="normalPadding relativePosition postItem">
                                    <?php if($index == 0){ ?>
                                        <div style="background-image: url(<?= Url::to(['analysis/image','attach'=>$news['thumb_url']]) ?>); height: 160px" class="backgroundCover relativePosition mainPostCover">
                                            <div class="bottomBar"><?= Html::encode($news['title']) ?></div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="flex-row">
                                            <div class="flex-col normalPadding"><?= Html::encode($news['title']) ?></div>
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
                                    <div class="flex-col"><a href="<?= Url::to(['send', 'data'=> $item['id'], 'mediaType' => $mediaType])?>"  title="群发" data-toggle='modal' data-target='#ajaxModal'><i class="fa fa-send"></i></a></div>
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
            <div class="row">
                <div class="col-sm-12">
                    <?= LinkPager::widget([
                        'pagination' => $pages,
                    ]);?>
                </div>
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

<!--瀑布流-->
<script type="text/javascript">
    var waterBasic = (function(){
        function init(){
            var nodeWidth = $(".normalPaddingRight").outerWidth(true),
                colNum = parseInt( $(window).width() / nodeWidth ),
                colSumHeight = [];
            for (var i=0;i<colNum;i++) {
                colSumHeight.push(0);
            }
            $(".normalPaddingRight").each(function(){
                var $cur = $(this),
                    idx = 0,
                    minSumHeight = colSumHeight[0];
                    maxSumHeight = colSumHeight[0];
                // 获取到solSumHeight中的最小高度
                for (var i=0;i<colSumHeight.length;i++) {
                    if (minSumHeight > colSumHeight[i]) {
                        minSumHeight = colSumHeight[i];
                        idx = i;
                    }

                    if (maxSumHeight < colSumHeight[i]) {
                        maxSumHeight = colSumHeight[i];
                    }
                }
                // 设置各个item的css属性
                $cur.css({
                    left: nodeWidth*idx,
                    top: minSumHeight
                });
                // 更新solSumHeight
                colSumHeight[idx] = colSumHeight[idx] + $cur.outerHeight(true);
            });

            $('.inlineBlockContainer').height(maxSumHeight)
        }
        // 设置窗口改变时也能重新加载
        $(window).on("resize", function(){
            init();
        });
        return {
            init: init
        }
    })();
    waterBasic.init();
</script>