## RESTful Api

目录

- 继承的基类说明
- 速率和参数配置
- 不需要速率控制设置
- Url权限配置
- 方法权限验证
- 返回数据格式修改
- 自定义code返回
- 解析Model首个报错信息
- 获取当前登录的用户信息

### 继承的基类说明

- 无需登录的控制器请全部继承 `api\controllers\OffAuthController`,注意Curd是改过的，不想用系统的Curd可直接继承 `yii\rest\ActiveController`
- 需登录的控制器请全部继承 `api\controllers\OnAuthController`,注意Curd是改过的，不想用系统的Curd可直接继承 `api\controllers\ActiveController`

### 速率和参数配置

> 可自行修改 `api/config/params.php` 配置

```
return [
    // 是否开启api log 日志记录
    'user.log' => true,
    'user.log.noPostData' => [ // 安全考虑，不接收Post存储到日志的路由
        'v1/site/login',
    ],
    // token有效期是否验证 默认不验证
    'user.accessTokenValidity' => false,
    // api接口token有效期 默认2天
    'user.accessTokenExpire' => 2 * 24 * 3600,
    // 不需要token验证的方法
    'user.optional' => [

    ],
    // 速度控制 60 秒内访问 50 次，注意，数组的第一个不要设置1，设置1会出问题，一定要大于2
    'user.rateLimit' => [50, 60],
    // 默认分页数量
    'user.pageSize' => 10,
];
```

### 不需要速率控制设置

找到 `common\models\api\AccessToken` 让其直接继承 `common\models\common\BaseModel` 即可

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
    if (in_array($action, ['delete', 'index']))
    {
        throw new \yii\web\BadRequestHttpException('权限不足');
    }
}
```

### 返回数据格式修改

> 请自行修改 `api\behaviors\beforeSend` 行为  
> 注意: 有些前端没有接触过状态码在Http头里面返回所以可以 在 beforeSend 数据处理后开启 `$response->statusCode = 200`;

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
use common\models\member\MemberInfo;

$tokenModel = Yii::$app->user->identity;
$member = MemberInfo::findIdentity($tokenModel['member_id']);
```
