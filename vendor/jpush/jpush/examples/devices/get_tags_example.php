<?php
require __DIR__ . '/../config.php';

// 获取Tag列表
$response = $client->device()->getDevices($registration_id);
print_r($response);