<?php
require __DIR__ . '/../config.php';

// 为一个标签添加设备
$result = $client->device()->isDeviceInTag($registration_id, 'tag');
$r = $result['body']['result'] ? 'true' : 'false';
print "before add device = " . $r . "\n";

print 'adding device ... response = ';
$response = $client->device()->addDevicesToTag('tag', $registration_id);
print_r($response);

$result = $client->device()->isDeviceInTag($registration_id, 'tag');
$r = $result['body']['result'] ? 'true' : 'false';
print "after add tags = " . $r . "\n\n";

// 为一个标签删除设备
$result = $client->device()->isDeviceInTag($registration_id, 'tag');
$r = $result['body']['result'] ? 'true' : 'false';
print "before remove device = " . $r . "\n";

print 'removing device ...  response = ';
$response = $client->device()->removeDevicesFromTag('tag', $registration_id);
print_r($response);

$result = $client->device()->isDeviceInTag($registration_id, 'tag');
$r = $result['body']['result'] ? 'true' : 'false';
print "after remove device = " . $r . "\n\n";

