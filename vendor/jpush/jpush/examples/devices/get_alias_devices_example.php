<?php
require __DIR__ . '/../config.php';

// 更新 Alias
$response = $client->device()->getAliasDevices('alias');
print_r($response);
