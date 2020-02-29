## SearchModel

- 示例一
- 示例二
- 示例三

> 该模型方便用户查询，不再每次都要单独的 SearchModel

```php
use common\models\base\SearchModel;
```

### 示例一

```php
$searchModel = new SearchModel([
   'model' => Topic::class,
   'scenario' => 'default',
]);

$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

return $this->render('index', [
   'dataProvider' => $dataProvider,
   'searchModel' => $searchModel,
]);
```
   
### 示例二


 ```php
$searchModel = new SearchModel([
   'defaultOrder' => ['id' => SORT_DESC],
   'model' => Topic::class,
   'scenario' => 'default',
   'relations' => ['comment' => []], // 关联表（可以是Model里面的关联）
   'partialMatchAttributes' => ['title'], // 模糊查询
   'pageSize' => 15
]);

$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
$dataProvider->query->andWhere([Topic::tableName() . '.user_id' => 23, Comment::tableName() . '.status' => 1]);

return $this->render('index', [
   'dataProvider' => $dataProvider,
   'searchModel' => $searchModel,
]);
  ```
  
  ### 示例三
  
  > 关联多表查询
  
```php
$searchModel = new SearchModel([
     'defaultOrder' => ['id' => SORT_DESC],
     'model' => Topic::class,
     'scenario' => 'default',
     'relations' => ['member' => ['nickname']], // 关联 member 表的 nickname 字段
     'partialMatchAttributes' => ['code', 'member.nickname'], // 模糊查询，注意 member_nickname 为关联表的别名 表名_字段
     'pageSize' => 15
]);

$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
$dataProvider->query->andWhere([Topic::tableName() . '.user_id' => 23, Comment::tableName() . '.status' => 1]);

return $this->render('index', [
     'dataProvider' => $dataProvider,
     'searchModel' => $searchModel,
]);
```

GridView 片段

```
[
    'attribute' => 'member.nickname',
    'label'=> '昵称',
    'filter' => Html::activeTextInput($searchModel, 'member.nickname', [
            'class' => 'form-control'
        ]
    ),
],
```

来源：https://getyii.com/topic/364


