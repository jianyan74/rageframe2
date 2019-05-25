<?php
require __DIR__ . '/../config.php';

$response = $client->device()->deleteAlias('alias');
print_r($response);
