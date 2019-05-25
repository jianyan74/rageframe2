# 目录

- [Init API](#init-api)
- [Push API](#push-api)
- [Report API](#report-api)
- [Device API](#device-api)
- [Schedule API](#schedule-api)
- [Exception Handle](#schedule-api)

> **注：PHP SDK 全面支持 namespaces 但为方便表达，以下例子都不使用 use 语句**

## Init API

在调用推送之前，我们必须先初始化 JPushClient，调用以下代码可以进行快速初始化：

```php
$client = new \JPush\Client($app_key, $master_secret);
```

在初始化 JPushClient 的时候，可以指定**日志路径**：

```php
$client = new \JPush\Client($app_key, $master_secret, $log_path);
```
> 默认日志路径为 `./jpush.log`,即保存在当前运行目录，如果想关闭日志，可以指定为 `null`。

## Push API

在初始化 JPushClient 后，调用以下代码将返回一个推送 Payload 构建器，它提供丰富的API来帮助你构建 PushPayload。

```php
$push = $client->push();
```

通过 [JPush Push API](https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/) 我们知道，一个 PushPayload 是由以下几个部分构成的：

- Cid
- Platform
- Audience
- Notification
- Message
- SmsContent
- Options

#### Cid

```php
$push->setCid($cid);
```

#### Platform

```php
$push->setPlatform('all');
// OR
$push->setPlatform('ios', 'android');
// OR
$push->setPlatform(['ios', 'android']);
```

#### Audience

```php
$push->addAllAudience();
```

```php
$push->addTag('tag1');
// OR
$push->addTag(['tag1', 'tag2']);
```

其他诸如 `addAlias()`, `addRegistrationId()`, `addTagAnd()`, `addTagNot()`, `addSegmentId()`, `addAbtest()` 的使用方法与 `addTag()` 类似，在此不做赘述。

#### Notification

```php
// 简单地给所有平台推送相同的 alert 消息
$push->setNotificationAlert('alert');
```

**iOS Notification**

```php
// iosNotification($alert = '', array $notification = array())
// 数组 $notification 的键支持 'sound', 'badge', 'content-available', 'mutable-content', category', 'extras', 'thread-id' 中的一个或多个

// 调用示例
$push->iosNotification();
// OR
$push->iosNotification('hello');
// OR
$push->iosNotification('hello', [
  'sound' => 'sound',
  'badge' => '+1',
  'extras' => [
    'key' => 'value'
  ]
]);
```

参数说明:

| 参数 | 说明 |
| --- | --- |
| alert |表示通知内容，会覆盖上级统一指定的 alert 信息；默认内容可以为空字符串，表示不展示到通知栏, 支持字符串和数组两种形式 |
| sound | 表示通知提示声音，默认填充为空字符串 |
| badge | 表示应用角标，把角标数字改为指定的数字；为 0 表示清除，支持 '+1','-1' 这样的字符串，表示在原有的 badge 基础上进行增减，默认填充为 '+1' |
| content-available | 表示推送唤醒，仅接受 true 表示为 Background Remote Notification，若不填默认表示普通的 Remote Notification |
| mutable-content | 表示通知扩展, 仅接受 true 表示支持 iOS10 的 UNNotificationServiceExtension, 若不填默认表示普通的 Remote Notification |
| category | IOS8才支持。设置 APNs payload 中的 'category' 字段值 |
| thread-id	 | 表示通知分组，ios 的远程通知通过该属性来对通知进行分组，同一个 thread-id 的通知归为一组 |
| extras | 表示扩展字段，接受一个数组，自定义 Key/value 信息以供业务使用 |

**Android Notification**

```php
// androidNotification($alert = '', array $notification = array())
// 调用示例同 IOS，数组 $notification 的键支持 'title', 'builder_id', 'priority', 'category', 'style', 'alert_type', 'big_text', 'inbox', 'big_pic_path', 'large_icon', 'intent', 'extras' 中的一个或多个
```

参数说明:

| 参数 | 说明 |
| --- | --- |
| alert | 表示通知内容，会覆盖上级统一指定的 alert 信息；默认内容可以为空字符串，表示不展示到通知栏 |
| title | 表示通知标题，会替换通知里原来展示 App 名称的地方 |
| builder_id | 表示通知栏样式 ID |
| priority | 表示通知栏展示优先级，默认为 0，范围为 -2～2 ，其他值将会被忽略而采用默认值 |
| category | 表示通知栏条目过滤或排序，完全依赖 rom 厂商对 category 的处理策略 |
| style | 表示通知栏样式类型，默认为 0，还有1，2，3可选，用来指定选择哪种通知栏样式，其他值无效。有三种可选分别为 bigText=1，Inbox=2，bigPicture=3 |
| alert_type | 表示通知提醒方式， 可选范围为 -1～7 ，对应 Notification.DEFAULT_ALL = -1 或者 Notification.DEFAULT_SOUND = 1， Notification.DEFAULT_VIBRATE = 2， Notification.DEFAULT_LIGHTS = 4 的任意 “or” 组合。默认按照 -1 处理。 |
| big_text | 表示大文本通知栏样式，当 style = 1 时可用，内容会被通知栏以大文本的形式展示出来，支持 api 16 以上的 rom |
| inbox | 表示文本条目通知栏样式，接受一个数组，当 style = 2 时可用，数组的每个 key 对应的 value 会被当作文本条目逐条展示，支持 api 16 以上的 rom |
| big_pic_path | 表示大图片通知栏样式，当 style = 3 时可用，可以是网络图片 url，或本地图片的 path，目前支持 .jpg 和 .png 后缀的图片。图片内容会被通知栏以大图片的形式展示出来。如果是 http／https 的 url，会自动下载；如果要指定开发者准备的本地图片就填 sdcard 的相对路径，支持 api 16 以上的 rom |
| large_icon | 表示通知栏大图标，图标路径可以是以 http 或 https 开头的网络图片，如："http:jiguang.cn/logo.png"，图标大小不超过 30k； 也可以是位于 drawable 资源文件夹的图标路径，如："R.drawable.lg_icon"；|
| intent | 表示扩展字段，接受一个数组，自定义 Key/value 信息以供业务使用 |
| extras | 表示扩展字段，接受一个数组，自定义 Key/value 信息以供业务使用 |

**WinPhone Notification**

```php
$push->addWinPhoneNotification($alert=null, $title=null, $_open_page=null, $extras=null)
```

参数说明:

| 参数 | 说明 |
| --- | --- |
| alert | 表示通知内容，会覆盖上级统一指定的 alert 信息；内容为空则不展示到通知栏 |
| title | 通知标题，会填充到 toast 类型 text1 字段上 |
| _open_page | 点击打开的页面名称 |

#### Message

```php
// message($msg_content, array $msg = array())
// 数组 $msg 的键支持 'title', 'content_type', 'extras' 中的一个或多个

// 调用示例
$push->message('Hello JPush');
// OR
$push->message('Hello JPush', [
  'title' => 'Hello',
  'content_type' => 'text',
  'extras' => [
    'key' => 'value'
  ]
]);
```

参数说明:

| 参数 | 说明 |
| --- | --- |
| msg_content | 消息内容本身 |
| title | 消息标题 |
| content_type | 消息内容类型 |
| extras | 表示扩展字段，接受一个数组，自定义 Key/value 信息以供业务使用 |

#### Sms Message

```php
$push->setSms($delay_time, $temp_id, array $temp_para = [])
```

参数说明:
* delay_time: 表示短信发送的延迟时间，单位为秒，不能超过 24 小时(即大于等于 0 小于等于 86400)。仅对 android 平台有效。
* temp_id: 短信补充的内容模板 ID。没有填写该字段即表示不使用短信补充功能。
* temp_para: 短信模板中的参数

##### 已弃用

```php
$push->setSmsMessage($content, $delay_time)
```

参数说明:
* content: 短信文本，不超过 480 字符
* delay_time: 表示短信发送的延迟时间，单位为秒，不能超过 24 小时(即大于等于 0 小于等于 86400)。仅对 android 平台有效。默认为 0，表示立即发送短信

#### Options

```php
// options(array $opts = array())
// 数组 $opts 的键支持 'sendno', 'time_to_live', 'override_msg_id', 'apns_production', 'big_push_duration', 'apns_collapse_id' 中的一个或多个
```

参数说明:

| 可选项 | 说明 |
| --- | --- |
| sendno | 表示推送序号，纯粹用来作为 API 调用标识，API 返回时被原样返回，以方便 API 调用方匹配请求与返回 |
| time_to_live | 表示离线消息保留时长(秒)，推送当前用户不在线时，为该用户保留多长时间的离线消息，以便其上线时再次推送。默认 86400 （1 天），最长 10 天。设置为 0 表示不保留离线消息，只有推送当前在线的用户可以收到 |
| override_msg_id | 表示要覆盖的消息ID，如果当前的推送要覆盖之前的一条推送，这里填写前一条推送的 msg_id 就会产生覆盖效果 |
| apns_production | 表示 APNs 是否生产环境，True 表示推送生产环境，False 表示要推送开发环境；如果不指定则默认为推送开发环境 |
| apns_collapse_id | APNs 新通知如果匹配到当前通知中心有相同 apns-collapse-id 字段的通知，则会用新通知内容来更新它，并使其置于通知中心首位；collapse id 长度不可超过 64 bytes|
| big_push_duration | 表示定速推送时长(分钟)，又名缓慢推送，把原本尽可能快的推送速度，降低下来，给定的 n 分钟内，均匀地向这次推送的目标用户推送。最大值为1400.未设置则不是定速推送 |

#### Common Method

```php
// 发送推送
// 该方法内部将自动调用构建方法获得当前构建对象，并转化为 JSON 向 JPush 服务器发送请求
$push->send();
```

> 构建 PushPayload 的 API 每一次都会返回自身的引用，所以我们可用使用链式调用的方法提高代码的简洁性，如:

```php
$response = $push()
    ->setCid('xxxxxx')
    ->setPlatform(['ios', 'android'])
    ->addTag(['tag1', 'tag2'])
    ->setNotificationAlert('Hello, JPush')
    ->iosNotification('hello', [
      'sound' => 'sound',
      'badge' => '+1',
      'extras' => [
        'key' => 'value'
      ]
    ])
    ->androidNotification('hello')
    ->message('Hello JPush', [
      'title' => 'Hello',
      'content_type' => 'text',
      'extras' => [
        'key' => 'value'
      ]
    ])
    ->send();

// OR 也可以提前准备好所有的参数，然后链式调用，这样代码可读性更好一点
$cid = 'xxxxxx';
$platform = array('ios', 'android');
$alert = 'Hello JPush';
$tag = array('tag1', 'tag2');
$regId = array('rid1', 'rid2');
$ios_notification = array(
    'sound' => 'hello jpush',
    'badge' => 2,
    'content-available' => true,
    'category' => 'jiguang',
    'extras' => array(
        'key' => 'value',
        'jiguang'
    ),
);
$android_notification = array(
    'title' => 'hello jpush',
    'builder_id' => 2,
    'extras' => array(
        'key' => 'value',
        'jiguang'
    ),
);
$content = 'Hello World';
$message = array(
    'title' => 'hello jpush',
    'content_type' => 'text',
    'extras' => array(
        'key' => 'value',
        'jiguang'
    ),
);
$options = array(
    'sendno' => 100,
    'time_to_live' => 100,
    'override_msg_id' => 100,
    'big_push_duration' => 100
);
$response = $push->setCid($cid)
    ->setPlatform($platform)
    ->addTag($tag)
    ->addRegistrationId($regId)
    ->iosNotification($alert, $ios_notification)
    ->androidNotification($alert, $android_notification)
    ->message($content, $message)
    ->options($options)
    ->send();
```

#### 获取 Cid

```php
$push->getCid($count = 1, $type = 'push');
```

## Report API

```php
 $report = $client->report();
 ```

#### 获取送达统计

```php
$report->getReceived('msg_id');
// OR
$report->getReceived(['msg_id1', 'msg_id2']);
```

#### 送达状态查询

```php
$report->getMessageStatus('msg_id0', 'rid0')
# OR
$report->getMessageStatus('msg_id0', ['rid0', 'rid1'])
#OR
$report->getMessageStatus('msg_id0', ['rid0', 'rid1'], '2017-12-21')
```

#### 获取消息统计

```php
// getMessages(getMessages($msgIds));
// 消息统计与送达统计一样，接受一个数组的参数，在这里不做赘述
```

#### 获取用户统计

调用一下代码可以获得用户统计

```php
$report->getUsers($time_unit, $start, $duration)
```

参数说明:

- time_unit:`String` 时间单位, 可取值HOUR, DAY, MONTH
- start:`String` 起始时间
    - 如果单位是小时，则起始时间是小时（包含天），格式例：2014-06-11 09
    - 如果单位是天，则起始时间是日期（天），格式例：2014-06-11
    - 如果单位是月，则起始时间是日期（月），格式例：2014-06
- duration:`String` 持续时长
    - 如果单位是天，则是持续的天数。以此类推
    - 只支持查询60天以内的用户信息，对于time_unit为HOUR的，只支持输出当天的统计结果。

## Device API

```php
$device = $client->device();
```

#### 操作 Device(registration_id)

```php
// 查询指定设备的别名与标签
$device->getDevices($registration_id);


// 更新指定设备的别名与标签

// 更新 Alias
$device->updateAlias($registration_id, 'alias');
// 添加 tag, 支持字符串和数组两种参数
$device->addTags($registration_id, 'tag');
// OR
$device->addTags($registration_id, ['tag1', 'tag2']);
// 移除 tag，支持字符串和数组两种参数
$device->removeTags($registration_id, 'tags');
// OR
$device->removeTags($registration_id,  ['tag1', 'tag2']);
// 清空所有 tag
$device->clearTags($registration_id);

// 更新 mobile
$device->updateMoblie($registration_id, '13800138000');
// 取消手机绑定
$device->clearMobile($registration_id);

// getDevicesStatus($registrationId)
// 获取在线用户的登录状态（VIP专属接口）,支持字符串和数组两种参数
$device->getDevicesStatus('rid');
// OR
$device->getDevicesStatus(['rid1', 'rid2']);
```

#### 操作标签

```php
// 获取标签列表
$device->getTags()

// 判断指定设备是否在指定标签之下
$device->isDeviceInTag($registrationId, $tag);


// 更新标签

// 为标签添加设备，支持字符串和数组两种参数
$device->addDevicesToTag($tag, 'rid');
$device->addDevicesToTag($tag, ['rid1', 'rid2']);

// 为标签移除设备，支持字符串和数组两种参数
$device->removeDevicesFromTag($tag, 'rid');
$device->removeDevicesFromTag($tag, ['rid1', 'rid2']);


// 删除标签
$device->deleteTag('tag');
```

#### 操作别名

```php
// 获取指定别名下的设备
$device->getAliasDevices('alias');

// 删除别名
$device->deleteAlias('alias');
```

## Schedule API

```php
$schedule = $client->schedule();
```

#### 创建定时任务

定时任务分为Single与Periodical两种，可以通过调用以下方法创建定时任务

```php
$schedule->createSingleSchedule($name, $push_payload, $trigger)
$schedule->createPeriodicalSchedule($name, $push_payload, $trigger)
```

参数说明:
- name: `String` 定时任务的名称
- push_payload: `PushPayload` Push的构建对象，通过Push模块的`build()`方法获得
- trigger: `Array` 触发器对象

#### 更新定时任务

```php
$schedule->updateSingleSchedule($schedule_id, $name=null, $enabled=null, $push_payload=null, $trigger=null)
$schedule->updatePeriodicalSchedule($schedule_id, $name=null, $enabled=null, $push_payload=null, $trigger=null)
```

#### 其他

```php
// 获取定时任务列表
$schedule->getSchedules($page=1);

// 获取指定定时任务
$schedule->getSchedule($schedule_id);

// 删除指定定时任务
$schedule->deleteSchedule($schedule_id);

// 获取定时任务对应的所有 msg_id
$schedule->getMsgIds($schedule_id);
```

## Exception Handle

当 API 请求发生错误时，SDK 将抛出异常，Pushpayload 具体错误代码请参考[ API 错误代码表](https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/#_19)。
PHP SDK 主要抛出两个异常 `\JPush\Exceptions\APIConnectionException` 和 `\JPush\Exceptions\APIRequestException` 分别对应请求连接产生的异常和请求响应的异常。
这两种异常都需要捕获，为简单起见，也可以捕获他们的父类异常 `JPush\Exceptions\JPushException`（见 README）。另外 APIRequestException 异常还提供其他方法供开发者调用。

```php
try {
    $pusher->send();
} catch (\JPush\Exceptions\APIConnectionException $e) {
    // try something here
    print $e;
} catch (\JPush\Exceptions\APIRequestException $e) {
    // try something here
    print $e;
}
```
