## RESTful Api

目录

- 继承的基类说明
- 速率和参数配置
  - 不需要速率控制设置
- 签名验证
- Url权限配置
- 方法权限验证
- 返回数据格式修改
- 自定义code返回
- 解析Model首个报错信息
- 获取当前登录的用户信息

> ！！！  
> ！！！
> ！！！  
> 注意：添加好控制器后必须配置rule才能正常访问，具体查看`api/config/main.php` 的路由规则示例

### 继承的基类说明

- 控制器请全部继承 `api\controllers\OnAuthController`,注意Curd是改过的，不想用系统的Curd可直接继承 `api\controllers\ActiveController`，如果设置控制器内方法不需要验证请设置 `optional` 属性
- 用户私有控制器请全部继承 `api\controllers\UserAuthController`

### 速率和参数配置

> 可自行修改 `common\models\common\RateLimit` 配置

##### 不需要速率控制设置

找到 `common\models\api\AccessToken` 让其直接继承 `common\models\base\BaseModel` 即可

### 签名验证

> 可自行修改 `api/config/params.php` 配置

测试控制器：`api\controllers\SignSecretKeyController`，注意要先开启路由规则匹配才能访问

```
return [
    // 签名验证默认不关闭验证，如果开启需了解签名生成及验证
    'user.httpSignValidity' => false,
    // 签名授权公钥秘钥
    'user.httpSignAccount' => [
        'doormen' => 'e3de3825cfbf',
    ],
];
```

### Url权限配置

> 系统默认都是严格校验url方式注意在 `api/config/main.php的urlManager`里添加规则，否则访问都是404。
> 如果不想严格校验请在 urlManager 里面注释或删除 `'enableStrictParsing' => true,`

### 方法权限验证

> 在执行访问方法前系统会先调用 `checkAccess` 方法来检测该方法能不能被验证通过，可以在控制器内添加该方法来判断权限，如果不需要可忽略。下面是个例子，不想让外部访问 delete 和 index 方法

```
/**
 * 权限验证
 *
 * @param string $action 当前的方法
 * @param null $model 当前的模型类
 * @param array $params $_GET变量
 * @throws \yii\web\BadRequestHttpException
 */
public function checkAccess($action, $model = null, $params = [])
{
    // 方法名称
    if (in_array($action, ['delete', 'index'])) {
        throw new \yii\web\BadRequestHttpException('权限不足');
    }
}
```

### 返回数据格式修改

> 请自行修改 `api\behaviors\BeforeSend` 行为  
> 注意: 有些前端没有接触过状态码在Http头里面返回所以可以 在 BeforeSend 数据处理后开启 `$response->statusCode = 200`;

### 自定义code返回

```
/**
 * 返回json数据格式
 *
 * 注意：要符合http状态码 否则报错
 * 
 * @param int $code 状态码
 * @param string $message 返回的报错信息
 * @param array|object $data 返回的数据结构
 */
api\controllers\ResultDataHelper::api($code, $message, $data = []);
```

### 解析Model首个报错信息

```
/**
 * 注意这里传递的变量一定是 `$model->getFirstErrors()` 的数据
 *
 * @param $fistErrors
 * @return string
 */
$this->analyErr($model->getFirstErrors())
```

### 获取当前登录的用户信息

```
use common\models\member\Member;

$tokenModel = Yii::$app->user->identity;
$member = Yii::$app->services->member->get($tokenModel['member_id']);
```
