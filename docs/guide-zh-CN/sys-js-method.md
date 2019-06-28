## 系统JS

目录

- 弹出框
- 内页打开新标签页面
- Ajax更新数据
- Js模板引擎Demo
- 用Iframe进行表单提交

### 弹出框

```
/**
* 错误提示
* 
* @param string title 标题
* @param string text 副标题
*/
rfError(title,text);

// 警告提示
rfWarning(title,text)

// 普通提示
rfAffirm(title,text)

// 信息提示
rfInfo(title,text)

// 成功提示
rfSuccess(title,text)

// 消息提示，layer风格
rfMsg(title)
```

删除二次确认

```
<a href="<?= Url::to(['delete','id' => $model['id']])?>"  onclick="rfDelete(this);return false;">
    <span class="btn btn-warning btn-sm">删除</span>
</a>
```

### 内页打开新标签页面

达到此效果只需要给此元素一个class为openContab，指定新窗口的链接地址为href   
> 注：并不仅限于a元素，任意元素只要给class为openContab， href 属性皆可打开新tab

```
 <a class="openContab" href="<?= Url::to(['test/index'])?>">测试标签</a>
```

### Ajax更新数据

> 注意tr上面的id为model主键

```
<tr id = "<?= $model['id']?>">
    <td>
         <?= \common\helpers\Html::sort($model['sort']); ?>
    </td>
    <td>
        <?= \common\helpers\Html::status($model['status']); ?>
    </td>
</tr>
```

### Js模板引擎Demo

页面模板

```
<script type="text/html" id="listModel">
    {{each data as value i}}
    <tr id = "{{value.id}}">
        <td>
            <h4>{{value.title}}</h4>
        </td>
    </tr>
    {{/each}}
</script>
```

获取数据并渲染

```
$.ajax({
    type:"get",
    url:"",
    dataType: "json",
    success: function(data){
        if (data.code == 200) {
            var html = template('listModel', data);
            // 渲染添加数据
            $('#listAddons').append(html);
        } else {
            rfAffirm(data.message);
        }
    }
});
```

相关文档：https://github.com/aui/art-template/wiki/syntax:simple

### 用Iframe进行表单提交

```
// class带上 openIframe 即可，提交表单默认id为w0，具体案例看 功能案例->Curd Grid
<?= Html::create(['edit'], '创建', [
        'class' => 'btn btn-primary btn-xs openIframe',
]); ?>
```