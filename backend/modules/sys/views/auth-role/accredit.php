<?php
use yii\widgets\ActiveForm;

$this->title = '角色授权';
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-content">
                <?php $form = ActiveForm::begin([]); ?>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php foreach($auth as $item){ ?>
                                <div class="checkbox i-checks page-header" style="margin-bottom: 0px;">
                                    <label class="checkbox-inline i-checks">
                                        <div class="icheckbox_square-green" style="position: relative;">
                                            <input type="checkbox" value="<?= $item['name']?>" name="auth[]" <?php if(!empty($item['authItemChildren0'])){ ?>checked="checked"<?php } ?>>
                                        </div><b><?= $item['description']?></b>
                                    </label>
                                </div>
                                <div class="check-list">
                                    <?php if(!empty($item['-'])){ ?>
                                        <?= $this->render('accredit_tree', [
                                            'models'=>$item['-'],
                                        ])?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- 加入csrf验证-->
                    <input name="parent" type="hidden" value="<?= $parent ?>">
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary">保存内容</button>
                            <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        // 复选框选中
        $('input').on('ifChecked', function(event){
            $(this).parent().parent().parent().parent().next('.check-list').iCheck('check');
            $(this).parent().parent().parent().next('.row').iCheck('check');
        });
        // 复选框移除
        $('input').on('ifUnchecked', function(event){
            $(this).parent().parent().parent().parent().next('.check-list').iCheck('uncheck');
            $(this).parent().parent().parent().next('.row').iCheck('uncheck');
        });
    });
</script>