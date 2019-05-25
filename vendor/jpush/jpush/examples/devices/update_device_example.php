<?php
require __DIR__ . '/../config.php';

// 更新 Alias
$result = $client->device()->getDevices($registration_id);
print "before update alias = " . $result['body']['alias'] . "\n";

print 'updating alias ... response = ';
$response = $client->device()->updateAlias($registration_id, 'jpush_alias');
print_r($response);

$result = $client->device()->getDevices($registration_id);
print "after update alias = " . $result['body']['alias'] . "\n\n";

// 添加 tag
$result = $client->device()->getDevices($registration_id);
print "before add tags = [" . implode(',', $result['body']['tags']) . "]\n";

print 'add tag1 tag2 ... response = ';

$response = $client->device()->addTags($registration_id, 'tag0');
print_r($response);

$response = $client->device()->addTags($registration_id, ['tag1', 'tag2']);
print_r($response);

$result = $client->device()->getDevices($registration_id);
print "after add tags = [" . implode(',', $result['body']['tags']) . "]\n\n";


// 移除 tag
$result = $client->device()->getDevices($registration_id);
print "before remove tags = [" . implode(',', $result['body']['tags']) . "]\n";

print 'removing tag1 tag2 ...  response = ';

$response = $client->device()->removeTags($registration_id, 'tag0');
print_r($response);

$response = $client->device()->removeTags($registration_id, ['tag1', 'tag2']);
print_r($response);

$result = $client->device()->getDevices($registration_id);
print "after remove tags = [" . implode(',', $result['body']['tags']) . "]\n\n";


// 更新 mobile
$result = $client->device()->getDevices($registration_id);
print "before update mobile = " . $result['body']['mobile'] . "\n";

print 'updating mobile ... response = ';
$response = $client->device()->updateMoblie($registration_id, '13800138000');
print_r($response);

$result = $client->device()->getDevices($registration_id);
print "after update mobile = " . $result['body']['mobile'] . "\n\n";