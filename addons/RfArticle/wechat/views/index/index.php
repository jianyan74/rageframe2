<?php
use common\helpers\Url;
use common\helpers\AddonHelper;

$this->title = '文章列表';
?>

<div id="wrap" class="article-list">
    <div id="mescroll" class="mescroll">
        <ul class="list" id="list"></ul>
    </div>
</div>
<script type="text/html" id="listTpl">
    {{each data item i}}
    <li>
        <a href="{{item.link}}">
            <div class="thumb">
                <img src="{{item.cover}}" alt="" />
            </div>
            <p class="title">{{item.title}}</p>
            <p class="time">{{item.created_at}}</p>
        </a>
    </li>
    {{/each}}
</script>

<script>
    $(function(){
        var loadData = function(page,mescroll){
            $.ajax({
                type:"get",
                url:"<?= Url::to(['index'])?>",
                dataType: "json",
                data: {
                    page:page.num,
                    rows_limit: page.size
                },
                success: function(res){
                    if(res.code == 200){
                        mescroll.endSuccess(res.data.length);
                        var html = template('listTpl', res);
                        $(".list").append(html);
                    }else{
                        mescroll.endSuccess();
                    }
                }
            });
        }

        var mescroll = new MeScroll("mescroll", {
            up: {
                auto: true,
                isBounce: false,
                noMoreSize: 3,
                empty: {
                    tip: "暂无相关数据~"
                },
                callback: loadData,
                page: {
                    num: 0,
                    size:4
                },
                clearEmptyId: "list",
                toTop:{
                    src : "<?= AddonHelper::file('img/mescroll-totop.png'); ?>"
                }
            }
        });
    })
</script>