## 系统JS

目录

- 弹出框
- ajax更新数据

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
```

删除二次确认

```
<a href="<?= Url::to(['delete','id' => $model['id']])?>"  onclick="rfDelete(this);return false;">
    <span class="btn btn-warning btn-sm">删除</span>
</a>
```

### ajax更新数据

> 注意tr上面的id为model主键

```
<tr id = "<?= $model['id']?>">
    <td>
        <input type="text" class="form-control" value="<?= $model['sort']?>" onblur="rfSort(this)">
    </td>
    <td>
        <?= \common\helpers\HtmlHelper::statusSpan($model['status']); ?>
    </td>
</tr>
```
