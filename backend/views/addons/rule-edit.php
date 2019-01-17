<?php
use yii\widgets\ActiveForm;
use common\models\wechat\RuleKeyword;
use common\helpers\HtmlHelper;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '规则管理', 'url' => ['rule', 'addon' => Yii::$app->params['addon']['name']]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([]); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="collapsed">回复规则</span>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="col-sm-9">
                            <?= $form->field($model, 'name')->textInput()->hint('您可以给这条规则起一个名字, 方便下次修改和查看。') ?>
                            <div class="setting" style="display: none">
                                <?= $form->field($model, 'sort')->textInput()->hint('规则优先级，越大则越靠前，最大不得超过255') ?>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <div class="checkbox m-2 m-r-xs" style="padding-top: 14px">
                                <label><input type="checkbox" id="setting" class="adv"> 高级设置</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-9">
                            <?= $form->field($model, 'keyword')->textInput(['value'=> implode(',',$ruleKeywords[RuleKeyword::TYPE_MATCH])])->hint('多个关键字请使用逗号隔开，如天气，今日天气。')->label('关键字') ?>
                            <div class="trigger" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">高级触发列表</label>
                                </div>
                                <div class="form-group well">
                                    <div class="tabs-container">
                                        <div class="tabs-top">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 包含关键字</a></li>
                                                <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false"> 正则表达式模式匹配</a></li>
                                                <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false"> 直接接管</a></li>
                                            </ul>
                                            <div class="tab-content ">
                                                <div id="tab-1" class="tab-pane active">
                                                    <div class="panel-body">
                                                        <table class="table table-hover">
                                                            <tbody id="list-key-<?= RuleKeyword::TYPE_INCLUDE?>">
                                                            <?php foreach ($ruleKeywords[RuleKeyword::TYPE_INCLUDE] as $value){ ?>
                                                                <tr>
                                                                    <td class="saveKeywordInput">
                                                                        <span class="key-hint"></span>
                                                                        <span class="key-text"><?= $value?></span>
                                                                        <input type="text" class="form-control key-input" name="ruleKey[<?= RuleKeyword::TYPE_INCLUDE ?>][]" value="<?= $value?>" style="display: none;">
                                                                    </td>
                                                                    <td type="<?= RuleKeyword::TYPE_INCLUDE ?>">
                                                                        <span class="btn btn-white saveKeyword edit-Keyword">编辑</span>
                                                                        <span class="btn btn-white" onclick="$(this).parent().parent().remove()">删除</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="panel-heading" type="<?= RuleKeyword::TYPE_INCLUDE?>">
                                                        <span class="btn btn-white addKeyword">添加包含关键字</span>
                                                        <span class="help-block ng-binding">用户进行交谈时，对话中包含上述关键字就执行这条规则。</span>
                                                    </div>
                                                </div>
                                                <div id="tab-2" class="tab-pane">
                                                    <div class="panel-body">
                                                        <table class="table table-hover">
                                                            <tbody id="list-key-<?= RuleKeyword::TYPE_REGULAR?>">
                                                            <?php foreach ($ruleKeywords[RuleKeyword::TYPE_REGULAR] as $value){ ?>
                                                                <tr>
                                                                    <td class="saveKeywordInput">
                                                                        <span class="key-hint"></span>
                                                                        <span class="key-text"><?= $value?></span>
                                                                        <input type="text" class="form-control key-input" name="ruleKey[<?= RuleKeyword::TYPE_REGULAR ?>][]" value="<?= $value?>" style="display: none;">
                                                                    </td>
                                                                    <td type="<?= RuleKeyword::TYPE_REGULAR ?>">
                                                                        <span class="btn btn-white saveKeyword edit-Keyword">编辑</span>
                                                                        <span class="btn btn-white" onclick="$(this).parent().parent().remove()">删除</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="panel-heading" type="<?= RuleKeyword::TYPE_REGULAR?>">
                                                        <span class="btn btn-white addKeyword">添加正则表达式模式</span>
                                                        <span class="help-block ng-binding">
                                                                用户进行交谈时，对话内容符合述关键字中定义的模式才会执行这条规则。<br>
                                                                <strong>注意：如果你不明白正则表达式的工作方式，请不要使用正则匹配</strong> <br>
                                                                <strong>注意：正则匹配使用MySQL的匹配引擎，请使用MySQL的正则语法</strong> <br>
                                                                <strong>示例: </strong><br>
                                                                <em>^微信</em>&nbsp;&nbsp;匹配以“微信”开头的语句<br>
                                                                <em>微信$</em>&nbsp;&nbsp;匹配以“微信”结尾的语句<br>
                                                                <em>^微信$</em>&nbsp;&nbsp;匹配等同“微信”的语句<br>
                                                                <em>微信</em>&nbsp;&nbsp;匹配包含“微信”的语句<br>
                                                                <em>[0-9.-]</em>&nbsp;&nbsp;匹配所有的数字，句号和减号<br>
                                                                <em>^[a-zA-Z_]$</em>&nbsp;&nbsp;所有的字母和下划线<br>
                                                                <em>^[[:alpha:]]{3}$</em>&nbsp;&nbsp;所有的3个字母的单词<br>
                                                                <em>^a{4}$</em>&nbsp;&nbsp;aaaa<br>
                                                                <em>^a{2,4}$</em>&nbsp;&nbsp;aa，aaa或aaaa<br>
                                                                <em>^a{2,}$</em>&nbsp;&nbsp;匹配多于两个a的字符串
                                                            </span>
                                                    </div>
                                                </div>
                                                <div id="tab-3" class="tab-pane">
                                                    <div class="panel-body">
                                                        <table class="table">
                                                            <tbody id="list-key-<?= RuleKeyword::TYPE_TAKE?>">
                                                            <?php foreach ($ruleKeywords[RuleKeyword::TYPE_TAKE] as $value){ ?>
                                                                <tr>
                                                                    <td>
                                                                        <span class="key-text">符合优先级条件时, 这条回复将直接生效</span>
                                                                        <input type="hidden" name="ruleKey[<?= RuleKeyword::TYPE_TAKE ?>][]" value="">
                                                                    </td>
                                                                    <td type="<?= RuleKeyword::TYPE_TAKE ?>">
                                                                        <span class="btn btn-white" onclick="$(this).parent().parent().remove()">取消接管</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="panel-heading" type="<?= RuleKeyword::TYPE_TAKE?>">
                                                        <span class="btn btn-white addKeyword">直接接管</span>
                                                        <span class="help-block ng-binding">
                                                                如果没有比这条回复优先级更高的回复被触发，那么直接使用这条回复。<br>
                                                                <strong>注意：如果你不明白这个机制的工作方式，请不要使用直接接管</strong>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <div class="checkbox m-2 m-r-xs" style="padding-top: 14px">
                                <label><input type="checkbox" id="trigger" class="adv"> 高级触发</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-9">
                            <?= $form->field($model, 'status')->radioList(['1' => '启用','0' => '禁用'])->hint('您可以临时禁用这条回复') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">　
    <div class="col-sm-12 text-center">
        <button class="btn btn-primary" type="submit">保存</button>
        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
    </div>
</div>
<?php ActiveForm::end(); ?>


<!--关键字模板-->
<script id="keylist" type="text/html">
    <tr>
        <td class="saveKeywordInput">
            <span class="key-hint"></span>
            <span class="key-text"></span>
            <input type="text" class="form-control key-input">
        </td>
        <td type="{{type}}">
            <span class="btn btn-white saveKeyword save-Keyword">保存</span>
            <span class="btn btn-white" onclick="$(this).parent().parent().remove()">删除</span>
        </td>
    </tr>
</script>

<!--直接授权模板-->
<script id="keytask" type="text/html">
    <tr>
        <td>
            <span class="key-text">符合优先级条件时, 这条回复将直接生效</span>
            <input type="hidden" name="ruleKey[{{type}}][]" value="1">
        </td>
        <td>
            <span class="btn btn-white" onclick="$(this).parent().parent().remove()">取消接管</span>
        </td>
    </tr>
</script>

<script>
    $(document).ready(function(){
        $('.adv').click(function () {
            var id = $(this).attr('id');
            if($(this).is(':checked')) {
                // do something
                $('.' + id).show();
            }else{
                $('.' + id).hide();
            }
        });

        // 添加
        var take = "<?= RuleKeyword::TYPE_TAKE?>";
        $('.addKeyword').click(function(){
            var self = $(this);
            var type = self.parent().attr('type');
            var data = [];
            data.type = type;
            var html = "";
            if(type == take){
                html += template('keytask', data);
                if($('#list-key-'+type+" tr").length < 1){
                    $('#list-key-'+type).append(html);
                }
            }else{
                html += template('keylist', data);
                $('#list-key-'+type).append(html);
            }
        });

        // 保存编辑
        $(document).on("click",".saveKeyword",function(){
            var self = $(this);
            var val = self.parent().parent().find('.key-input').val();
            var type = self.parent().attr('type');
            if (self.hasClass("save-Keyword")){// 保存
                if(val){
                    self.removeClass("save-Keyword").addClass("edit-Keyword");
                    self.text('编辑');
                    self.parent().parent().find('.key-text').text(val);
                    self.parent().parent().find('.key-text').show();
                    self.parent().parent().find('.key-input').hide();
                    self.parent().parent().find('.key-input').attr('name','ruleKey['+type+'][]');
                }
            } else {// 编辑
                self.removeClass("edit-Keyword").addClass("save-Keyword");
                self.text('保存');
                self.parent().parent().find('.key-text').hide();
                self.parent().parent().find('.key-input').show();
                self.parent().parent().find('.key-input').attr('name','');
            }
        });
    });
</script>