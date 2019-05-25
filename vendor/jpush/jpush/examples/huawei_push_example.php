<?php
// 这只是使用样例,不应该直接用于实际生产环境中 !!

require 'config.php';

try {
    $payload = $client->push()
        ->setPlatform(array('ios', 'android'))
        // ->addAlias('alias')
        ->addTag(array('tag1', 'tag2'))
        // ->addRegistrationId($registration_id)
        ->setNotificationAlert('Hi, JPush')
        ->androidNotification('Hello HUAWEI', array(
            'title' => 'huawei demo',

            // ---------------------------------------------------
            // `uri_activity` 字段用于指定想要打开的 activity.
            // 值为 activity 节点的 “android:name” 属性值。
            'uri_activity' => 'cn.jpush.android.ui.OpenClickActivity',
            // ---------------------------------------------------

            'extras' => array(
                'key' => 'value',
                'jiguang'
            ),
        ));
        // ->send();
        print_r($payload->build());

} catch (\JPush\Exceptions\APIConnectionException $e) {
    // try something here
    print $e;
} catch (\JPush\Exceptions\APIRequestException $e) {
    // try something here
    print $e;
}
