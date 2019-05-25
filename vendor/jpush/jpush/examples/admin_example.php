<?php
// 这只是使用样例,不应该直接用于实际生产环境中 !!

require 'config.php';

use JPush\AdminClient as Admin;

$admin = new Admin($dev_key, $dev_secret);
$response = $admin->createApp('aaa', 'cn.jpush.app');
print_r($response);

$appKey = $response['body']['app_key'];
$response = $admin->deleteApp($appKey);
print_r($response);
