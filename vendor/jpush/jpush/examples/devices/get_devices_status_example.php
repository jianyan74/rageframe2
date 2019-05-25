<?php
require __DIR__ . '/../config.php';

// 获取用户在线状态（VIP专属接口）
try {
    $response = $client->device()->getDevicesStatus($registration_id);
} catch(\JPush\Exceptions\APIRequestException $e) {
    print $e;
    print $e->getHttpCode();
    print $e->getHeaders();
}

print_r($response);
