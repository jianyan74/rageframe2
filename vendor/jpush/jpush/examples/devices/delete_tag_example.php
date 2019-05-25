<?php
require __DIR__ . '/../config.php';

$response = $client->device()->deleteTag('tag');
print_r($response);
