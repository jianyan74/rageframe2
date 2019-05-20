<?php
use common\helpers\Url;
use common\helpers\Html;

$this->title = '数据备份';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['backups'])?>"> 数据备份</a></li>
                <li class="active"><a href="<?= Url::to(['restore'])?>"> 数据还原</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>备份名称</th>
                            <th>卷数</th>
                            <th>压缩</th>
                            <th>数据大小</th>
                            <th>备份时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($list as $key => $row){ ?>
                            <tr data-time="<?= $row['time']?>">
                                <td><?= date('Ymd-His',$row['time'])?></td>
                                <td><?= $row['part']?></td>
                                <td><?= $row['compress']?></td>
                                <td><?= Yii::$app->formatter->asShortSize($row['size'], 0)?></td>
                                <td><?= Yii::$app->formatter->asDatetime($row['time'])?></td>
                                <td>
                                    <?= Html::a('还原', 'javascript:void(0);', [
                                        'class' => 'btn btn-info btn-sm table-restore'
                                    ])?>
                                    <?= Html::delete(['delete','time' => $row['time']])?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        var time;
        // 优化表单击
        $(".table-restore").click(function () {
            time = $(this).parent().parent().parent().attr('data-time');
            $.ajax({
                type: "post",
                url: "<?= Url::to(['restore-init'])?>",
                dataType: 'json',
                data: {time:time},
                success: function(data) {
                    if(data.code == 200){
                        var part = data.data.part;
                        var start = data.data.start;
                        startRestore(part,start);
                        rfAffirm('还原中,请不要关闭本页面,可能会造成服务器卡顿');
                    }else{
                        rfAffirm(data.message);
                    }
                }
            })

        });

        // 开始还原
        function startRestore(part,start) {
            $.ajax({
                type: "post",
                url: "<?= Url::to(['restore-start'])?>",
                dataType: 'json',
                data: {part:part,start:start},
                success: function(data) {
                    if(data.code == 200){
                        var achieveStatus = data.data.achieveStatus;
                        if(achieveStatus == 0){
                            startRestore(data.data.part, data.data.start);
                            rfAffirm('还原中,请不要关闭本页面,可能会造成服务器卡顿['+data.data.start+']......');
                        }else{
                            rfAffirm(data.message);
                        }
                    }else{
                        rfAffirm(data.message);
                    }
                }
            })
        }
    })
</script>