<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">回复内容</h4>
    </div>
    <div class="box-body">
        <div class="col-sm-12">
            <?= $form->field($moduleModel, 'api_url')->dropDownList(\common\models\wechat\ReplyUserApi::getList())->hint('1、添加此模块的规则后，只针对于单个规则定义有效，如果需要全部路由给接口处理，则修改该模块的优先级顺序<br>2、本地文件存放在公共文件夹内(/backend/modules/wechat/userapis)下<br>3、文件名格式为*Api.php，例如：TestApi.php') ?>
            <?= $form->field($moduleModel, 'default')->textInput()->hint('当接口无回复时，则返回用户此处设置的文字信息，优先级高于“默认关键字”') ?>
            <?= $form->field($moduleModel, 'cache_time')->textInput()->hint('接口返回数据将缓存在系统中的时限，默认为0不缓存') ?>
            <?= $form->field($moduleModel, 'description')->textarea()->hint('仅作为后台备注接口的用途') ?>
        </div>
        <div class="form-group">　
            <div class="col-sm-12 text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>