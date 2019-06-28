<?php
use common\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\Html;

$this->title = '视频';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<?= $this->render('_nav', [
    'allMediaType' => $allMediaType,
    'mediaType' => $mediaType,
    'keywords' => $keywords,
    'count' => $pages->totalCount
]); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="inlineBlockContainer col3 vAlignTop">
            <?php foreach ($models as $model){ ?>
                <div class="normalPaddingRight" style="width:20%;">
                    <div class="borderColorGray separateChildrenWithLine whiteBG m-b-sm">
                        <div class="normalPadding">
                            <div style="height: 160px;text-align:center;" class="backgroundCover relativePosition mainPostCover">
                                <i class="fa fa-music" style="font-size: 40px;margin:0 auto;padding-top: 40px"></i>
                                <div class="bottomBar"><?= $model['file_name'] ?></div>
                            </div>
                        </div>
                        <div class="flex-row hAlignCenter normalPadding postToolbar">
                            <div class="flex-col"><a href="<?= Url::to(['send', 'data'=> $model['media_id'], 'mediaType' => $mediaType])?>"  title="群发" data-toggle='modal' data-target='#ajaxModal'><i class="fa fa-send"></i></a></div>
                            <div class="flex-col"><a href="<?= Url::to(['preview','attach_id' => $model['id'], 'mediaType' => $mediaType])?>" title="手机预览" data-toggle='modal' data-target='#ajaxModal'><i class="fa fa-search"></i></a></div>
                            <div class="flex-col"><a href="<?= Url::to(['delete','attach_id' => $model['id'], 'mediaType' => $mediaType])?>" onclick="rfDelete(this, '删除后会删除素材对应的自动回复和等待群发');return false;" title="删除"><i class="fa fa-trash"></i></a></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?= LinkPager::widget([
            'pagination' => $pages,
        ]);?>
    </div>
</div>